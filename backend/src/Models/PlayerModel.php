<?php

namespace App\Models;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use App\Resource\AddPlayerRequest;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlayerModel
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param AddPlayerRequest $request
     * @return JsonResponse
     */
    public function addPlayer(AddPlayerRequest $request): JsonResponse
    {
        if ($request->isValid()) {
            $player = new Player();
            $player->setDivision($request->division)
                ->setEloRating($request->eloRating)
                ->setName($request->name)
                ->setPlayerId($request->playerId)
                ->setWins($request->wins)
                ->setLoses($request->loses);
            $this->entityManager->persist($player);
            $this->entityManager->flush();
            return new SuccessResponse([
                "playerId" => $player->getId()
            ]);
        } else {
            return new ErrorResponse('Sent data is not sufficient');
        }
    }

    public function getPlayerAll(): JsonResponse {
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $this->entityManager->getRepository(Player::class);

        $players = [];
        foreach ($playerRepository->findAll() as $player) {
            $players[] = $player->asArray();
        }

        return new SuccessResponse($players);
    }
}