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

use App\Helper\EloChangeCalculator;
use Symfony\Component\HttpFoundation\JsonResponse;

class HistoryModel
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var Player[] $playerMap */
    private $playerMap;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
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
        $historyEntry = new History();
        $historyEntry->setWinner($request->winner)
            ->setLoser($request->loser)
            ->setProofUrl($request->proofUrl);
        $this->entityManager->persist($historyEntry);


        $changes = $this->updateEloForPlayers($request);

        $this->entityManager->flush();
        return new SuccessResponse([
            "historyId" => $historyEntry->getId(),
            "changes" => $changes,
        ]);
    }

    private function updateEloForPlayers(AddHistoryRequest $request): array
    {
        $playerRepository = $this->getPlayerRepository();

        $eloCalc = new EloChangeCalculator(
            $playerRepository->findById($request->winner),
            $playerRepository->findById($request->loser)
        );

        return $eloCalc->updatePlayers();
    }

    public function getHistoryAll(): JsonResponse
    {
        return $this->createHistoryResponse($this->getHistoryRepository()->findAll());
    }

    public function getHistoryRecent(): JsonResponse
    {
        return $this->createHistoryResponse($this->getHistoryRepository()->findLastEntries(20));
    }

    /**
     * @param GetHistoryRequest $request
     * @return JsonResponse
     */
    public function getHistory(GetHistoryRequest $request): JsonResponse
    {
        $histories = $this->getHistoryRepository()->findWithHistoryEntity($request->history, $request->limit);
        return $this->createHistoryResponse($histories);
    }

    private function getPlayerMap(): array
    {
        $playerMap = [];

        foreach ($this->getPlayerRepository()->findAll() as $player) {
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
     * @return HistoryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function getHistoryRepository()
    {
        return $this->entityManager->getRepository(History::class);
    }

    /**
     * @return PlayerRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function getPlayerRepository()
    {
        return $this->entityManager->getRepository(Player::class);
    }
}