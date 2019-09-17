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
    /** @var PlayerRepository */
    private $playerRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param AddPlayerRequest $request
     * @return JsonResponse
     */
    public function addPlayer(AddPlayerRequest $request): JsonResponse
    {
        if ($request->isValid()) {
            if ($this->doesPlayerAlreadyExist($request)) {
                return new ErrorResponse(sprintf(ErrorResponse::PLAYER_DOES_ALREADY_EXIST, $request->name));
            }
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
            return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        }
    }

    public function getPlayerAll(): JsonResponse {
        $players = [];
        foreach ($this->playerRepository->findAll() as $player) {
            $players[] = $player->asArray();
        }

        return new SuccessResponse($players);
    }

    private function doesPlayerAlreadyExist(AddPlayerRequest $request)
    {
        return (
            $this->playerRepository->findByName($request->name) !== null
            || $this->playerRepository->findByPlayerId($request->playerId) !== null
        );
    }
}