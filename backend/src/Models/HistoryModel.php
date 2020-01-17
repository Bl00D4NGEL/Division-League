<?php

namespace App\Models;

use App\Repository\HistoryRepository;
use App\Repository\PlayerRepository;
use App\Repository\RosterRepository;
use App\Repository\TeamRepository;
use App\Resource\AddHistoryMultiRequest;
use App\Resource\AddHistoryRequest;
use App\Resource\GetHistoryRequest;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use App\Service\EloCalculator;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use JMS\Serializer\SerializerInterface;

use App\Entity\Player;
use App\Entity\History;

use Symfony\Component\HttpFoundation\JsonResponse;

final class HistoryModel
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SerializerInterface */
    private $serializer;

    /** @var PlayerRepository */
    private $playerRepository;

    /** @var HistoryRepository */
    private $historyRepository;

    /** @var RosterRepository */
    private $rosterRepository;

    /** @var TeamRepository */
    private $teamRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        PlayerRepository $playerRepository,
        RosterRepository $rosterRepository,
        TeamRepository $teamRepository,
        HistoryRepository $historyRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->playerRepository = $playerRepository;
        $this->historyRepository = $historyRepository;
        $this->rosterRepository = $rosterRepository;
        $this->teamRepository = $teamRepository;
    }

    /**
     * @param AddHistoryRequest $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addHistory(AddHistoryRequest $request): JsonResponse
    {
        if ($request->isValid()) {
            return $this->insertHistory($request);
        } else {
            return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        }
    }

    /**
     * @param AddHistoryRequest $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function insertHistory(AddHistoryRequest $request): JsonResponse
    {
        $winner = $this->playerRepository->find($request->winner);
        $loser = $this->playerRepository->find($request->loser);

        if ($winner === null || $loser === null) {
            return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        }

        $winnerTeam = $this->rosterRepository->getSoloTeamForPlayer($winner);
        if ($winnerTeam === null) {
            $winnerTeam = $this->rosterRepository->createSoloTeamForPlayer($winner);
        }

        $loserTeam = $this->rosterRepository->getSoloTeamForPlayer($loser);
        if ($loserTeam === null) {
            $loserTeam = $this->rosterRepository->createSoloTeamForPlayer($loser);
        }

        $eloCalculator = new EloCalculator($winner->getElo(), $loser->getElo());

        $history = new History();
        $history
            ->setLoser($loserTeam->getId())
            ->setWinner($winnerTeam->getId())
            ->setLoserGain($eloCalculator->getEloChangeForLoser())
            ->setWinnerGain($eloCalculator->getEloChangeForWinner())
            ->setProofUrl($request->proofUrl);
        $this->entityManager->persist($history);
        $this->entityManager->flush();
        return new SuccessResponse($this->formatHistoriesForResponse([$history]));
    }

    /**
     * @param AddHistoryMultiRequest $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addHistoryMulti(AddHistoryMultiRequest $request): JsonResponse
    {
        if ($request->isValid()) {
            try {
                $winnerTeam = $this->rosterRepository->getTeamForPlayers($request->winner);
                if ($winnerTeam === null) {
                    $winnerTeam = $this->rosterRepository->createTeamForPlayers($request->winner, $request->winnerTeamName);
                }

                $loserTeam = $this->rosterRepository->getTeamForPlayers($request->loser);
                if ($loserTeam === null) {
                    $loserTeam = $this->rosterRepository->createTeamForPlayers($request->loser, $request->loserTeamName);
                }

                $eloCalculator = new EloCalculator(
                    $this->getAverageEloForPlayers($request->winner),
                    $this->getAverageEloForPlayers($request->loser)
                );

                $history = new History();
                $history
                    ->setLoser($loserTeam->getId())
                    ->setWinner($winnerTeam->getId())
                    ->setLoserGain($eloCalculator->getEloChangeForLoser())
                    ->setWinnerGain($eloCalculator->getEloChangeForWinner())
                    ->setProofUrl($request->proofUrl);
                $this->entityManager->persist($history);
                $this->entityManager->flush();

                $winnerTeam->win($eloCalculator->getEloChangeForWinner());
                $this->entityManager->persist($winnerTeam);
                $loserTeam->lose($eloCalculator->getEloChangeForLoser());
                $this->entityManager->persist($loserTeam);
                $this->entityManager->flush();
                return new SuccessResponse($this->formatHistoriesForResponse([$history]));
            } catch (RuntimeException $e) {
                return new ErrorResponse($e->getMessage());
            }
        } else {
            return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        }
    }

    public function getHistoryAll(): JsonResponse
    {
        return new SuccessResponse($this->formatHistoriesForResponse($this->historyRepository->findAll()));
    }

    public function getHistoryRecent(): JsonResponse
    {
        return new SuccessResponse($this->formatHistoriesForResponse($this->historyRepository->findLastEntries(20)));
    }

    /**
     * @param GetHistoryRequest $request
     * @return JsonResponse
     */
    public function getHistory(GetHistoryRequest $request): JsonResponse
    {
        return new SuccessResponse($this->formatHistoriesForResponse($this->historyRepository->findWithHistoryEntity($request->history, $request->limit)));
    }

    /**
     * @param array $histories
     * @return array
     */
    private function formatHistoriesForResponse(array $histories): array
    {
        $historyData = [];
        foreach ($histories as $history) {
            $historyData[] = [
                "winner" => $this->mapPlayerArray($this->rosterRepository->getPlayersForTeam($history->getWinner())),
                "loser" => $this->mapPlayerArray($this->rosterRepository->getPlayersForTeam($history->getLoser())),
                "proofUrl" => $history->getProofUrl(),
                "winnerTeamName" => $this->teamRepository->getTeamName($history->getWinner()),
                "loserTeamName" => $this->teamRepository->getTeamName($history->getLoser()),
                "winnerEloWin" => $history->getWinnerGain(),
                "loserEloLose" => $history->getLoserGain(),
                "id" => $history->getId()
            ];
        }
        return $historyData;
    }

    private function mapPlayerArray(array $players)
    {
        return array_map(static function (Player $player) {
            return $player->asArray();
        }, $players);
    }

    /**
     * @param array $playerIds
     * @return int
     * @throws RuntimeException
     */
    private function getAverageEloForPlayers(array $playerIds): int
    {
        $players = $this->playerRepository->findMultipleByIds($playerIds);
        if (($players === null ? 0 : count($players)) !== count($playerIds)) {
            throw new RuntimeException('Not all players were found in the database');
        }
        return ceil(
            array_sum(
                array_map(function (Player $val) {
                    return $val->getElo();
                }, $players)
            ) / count($playerIds)
        );
    }
}
