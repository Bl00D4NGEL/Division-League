<?php

namespace App\Models;

use App\Repository\HistoryRepository;
use App\Repository\PlayerRepository;
use App\Resource\AddHistoryRequest;
use App\Resource\GetHistoryRequest;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;

use App\Entity\Player;
use App\Entity\History;

use Symfony\Component\HttpFoundation\JsonResponse;

class HistoryModel
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SerializerInterface */
    private $serializer;

    /** @var Player[] */
    private $playerMap;

    /** @var PlayerRepository */
    private $playerRepository;

    /** @var HistoryRepository */
    private $historyRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        PlayerRepository $playerRepository,
        HistoryRepository $historyRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->playerRepository = $playerRepository;
        $this->historyRepository = $historyRepository;
    }

    /**
     * @param AddHistoryRequest $request
     * @return JsonResponse
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
     */
    private function insertHistory(AddHistoryRequest $request): JsonResponse
    {
        $winner = $this->playerRepository->findById($request->winner);
        $loser = $this->playerRepository->findById($request->loser);
        if ($winner !== null && $loser !== null) {
            $historyId = $this->updateWinnerAndLoserInDatabase($request->proofUrl, $winner, $loser);
            if ($historyId !== null) {
                return new SuccessResponse([
                    "historyId" => $historyId,
                    "winner" => $winner->asArray(),
                    "loser" => $loser->asArray()
                ]);
            }
            return new ErrorResponse(ErrorResponse::ERROR_PERSISTING_DATA);
        }
        return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
    }

    public function getHistoryAll(): JsonResponse
    {
        return $this->createHistoryResponse($this->historyRepository->findAll());
    }

    public function getHistoryRecent(): JsonResponse
    {
        return $this->createHistoryResponse($this->historyRepository->findLastEntries(20));
    }

    /**
     * @param GetHistoryRequest $request
     * @return JsonResponse
     */
    public function getHistory(GetHistoryRequest $request): JsonResponse
    {
        $histories = $this->historyRepository->findWithHistoryEntity($request->history, $request->limit);
        return $this->createHistoryResponse($histories);
    }

    private function getPlayerMap(): array
    {
        $playerMap = [];

        foreach ($this->playerRepository->findAll() as $player) {
            $playerMap[$player->getId()] = $this->serializer->serialize($player, 'json');
        }
        return $playerMap;
    }

    /**
     * @param array $playerMap
     * @param History $history
     * @return array
     */
    private function createResponseHistory(array &$playerMap, History &$history): array
    {
        return [
            "winner" => json_decode($playerMap[$history->getWinner()]),
            "loser" => json_decode($playerMap[$history->getLoser()]),
            "proofUrl" => $history->getProofUrl(),
            "id" => $history->getId()
        ];
    }

    /**
     * @param array $histories
     * @return array
     */
    private function createResponseHistories(array $histories): array
    {
        if (null === $this->playerMap) {
            $this->playerMap = $this->getPlayerMap();
        }
        $responseHistories = [];
        foreach ($histories as $history) {
            $responseHistories[] = $this->createResponseHistory($this->playerMap, $history);
        }
        return $responseHistories;
    }


    /**
     * @param History[] $histories
     * @return SuccessResponse
     */
    private function createHistoryResponse(array $histories)
    {
        return new SuccessResponse($this->createResponseHistories($histories));
    }

    /**
     * @param string $proofUrl
     * @param Player|null $winner
     * @param Player|null $loser
     * @return int|null
     */
    private function updateWinnerAndLoserInDatabase(string $proofUrl, Player $winner, Player $loser): ?int
    {
        $historyEntry = new History();
        $historyEntry->setWinner($winner->getId())
            ->setEloWinWinner($winner->calculateEloChangeForWinAgainst($loser))
            ->setLoser($loser->getId())
            ->setEloLoseLoser($loser->calculateEloChangeForWinAgainst($winner))
            ->setProofUrl($proofUrl);
        $this->entityManager->persist($historyEntry);

        $winner->winAgainst($loser);
        $loser->loseAgainst($winner);
        $this->entityManager->flush();
        return $historyEntry->getId();
    }
}