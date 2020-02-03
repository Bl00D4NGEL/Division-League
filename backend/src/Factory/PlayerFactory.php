<?php

namespace App\Factory;

use App\Entity\Player;
use App\Resource\AddPlayerRequest;

class PlayerFactory
{
    public function createFromRequest(AddPlayerRequest $request): Player
    {
        return (new Player())
            ->setDivision($request->division)
            ->setElo($request->elo)
            ->setName($request->name)
            ->setPlayerId($request->playerId)
            ->setLeague($request->league)
            ->setWins($request->wins)
            ->setLoses($request->loses);
    }
}
