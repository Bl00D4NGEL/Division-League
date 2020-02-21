<?php

namespace App\Models;

use App\Entity\Player;
use App\Factory\PlayerFactory;
use App\Repository\PlayerRepository;
use App\Resource\AddPlayerRequest;
use App\Resource\DeletePlayerRequest;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlayerModel
{
    /** @var PlayerRepository */
    private $playerRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var PlayerFactory */
    private $playerFactory;

    public function __construct(EntityManagerInterface $entityManager, PlayerRepository $playerRepository, PlayerFactory $playerFactory)
    {
        $this->playerRepository = $playerRepository;
        $this->entityManager = $entityManager;
        $this->playerFactory = $playerFactory;
    }

    /**
     * @param AddPlayerRequest $request
     * @return JsonResponse
     */
    public function addPlayer(AddPlayerRequest $request): JsonResponse
    {
        if (!$request->isValid()) {
            return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        }
        $existingPlayer = $this->getPlayer($request);
        if (null !== $existingPlayer) {
            if (true === $existingPlayer->isDeleted()) {
                return new ErrorResponse(sprintf('Player %s already existed and had %d elo but has been deleted.', $existingPlayer->getName(), $existingPlayer->getElo()));
            }
            return new ErrorResponse(sprintf(ErrorResponse::PLAYER_DOES_ALREADY_EXIST, $request->name));
        }
        $player = $this->playerFactory->createFromRequest($request);

        $this->entityManager->persist($player);
        $this->entityManager->flush();
        return new SuccessResponse([
            "playerId" => $player->getId()
        ]);
    }

    public function addDeletedPlayer(AddPlayerRequest $request): JsonResponse {
        if (!$request->isValid()) {
            return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        }
        $existingPlayer = $this->getPlayer($request);
        if (null === $existingPlayer) {
            return new ErrorResponse('Player does not exist');
        }

        if (false === $existingPlayer->isDeleted()) {
            return new ErrorResponse('Player is not deleted');
        }

        $existingPlayer->setDeleted(false);

        $this->entityManager->persist($existingPlayer);
        $this->entityManager->flush();
        return new SuccessResponse([
            "message" => "Player has been added again"
        ]);
    }

    public function getPlayerAll(): JsonResponse
    {
        $players = [];
        foreach ($this->playerRepository->findAll() as $player) {
            $players[] = $player->asArray();
        }

        return new SuccessResponse($players);
    }

    private function getPlayer(AddPlayerRequest $request): ?Player {
        $player =  $this->playerRepository->findByName($request->name);
        if (null !== $player) {
            return $player;
        }

        $player =  $this->playerRepository->findByPlayerId($request->playerId);
        if (null !== $player) {
            return $player;
        }

        return null;
    }

    public function playerDelete(DeletePlayerRequest $request): JsonResponse
    {
        if (!$request->isValid()) {
            return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        }

        try {
            $this->playerRepository->deleteById($request->id);
        } catch (ORMException $ORMException) {
            return new ErrorResponse('Deletion of player failed');
        } catch (\Exception $e) {
            return new ErrorResponse($e->getMessage());
        }
        return new SuccessResponse();
    }
}
