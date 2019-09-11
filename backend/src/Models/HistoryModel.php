<?php

namespace App\Models;

use App\Repository\HistoryRepository;
use App\Repository\PlayerRepository;
use App\Resource\AddHistoryRequest;
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
            $historyObject = $this->insertHistory($request);
            $changes = $this->updateEloForPlayers($request);

            $this->entityManager->flush();

            return new SuccessResponse([
                "historyId" => $historyObject->getId(),
                "changes" => $changes,
            ]);
        } else {
            return new ErrorResponse('Sent data is not sufficient');
        }
    }

    /**
     * @param AddHistoryRequest $request
     * @return History
     */
    private function insertHistory(AddHistoryRequest $request): History
    {
        $historyEntry = new History();
        $historyEntry->setWinnerId($request->winner)
            ->setLoserId($request->loser)
            ->setProofUrl($request->proofUrl);
        $this->entityManager->persist($historyEntry);
        return $historyEntry;
    }

    private function updateEloForPlayers(AddHistoryRequest $request): array
    {
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $this->entityManager->getRepository(Player::class);

        $eloCalc = new EloChangeCalculator();
        $eloCalc->setWinner($playerRepository->findById($request->winner));
        $eloCalc->setLoser($playerRepository->findById($request->loser));

        return $eloCalc->updatePlayers();
    }

    public function getHistoryAll(): JsonResponse
    {
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $this->entityManager->getRepository(Player::class);
        $players = $playerRepository->findAll();
        $playerMap = [];

        foreach ($players as $player) {
            $playerMap[$player->getId()] = $this->serializer->serialize($player, 'json');
        }

        /** @var HistoryRepository $historyRepository */
        $historyRepository = $this->entityManager->getRepository(History::class);
        $histories = $historyRepository->findAll();
        $responseHistories = [];
        foreach ($histories as $historyObject) {
            $responseHistories[] = [
                "winner" => json_decode($playerMap[$historyObject->getWinnerId()]),
                "loser" => json_decode($playerMap[$historyObject->getLoserId()]),
                "proofUrl" => $historyObject->getProofUrl(),
                "id" => $historyObject->getId()
            ];
        }
        return new SuccessResponse($responseHistories);
    }


}