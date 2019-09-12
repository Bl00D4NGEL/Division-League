<?php

namespace App\Helper;

use App\Entity\Player;

class EloChangeCalculator
{
    /** @var Player */
    private $winner;

    /** @var Player */
    private $loser;

    public function __construct(Player $winner, Player $loser)
    {
        $this->winner = $winner;
        $this->loser = $loser;
    }

    private function getKFactor(): float
    {
        $kFactor = ($this->winner->getEloRating() + $this->loser->getEloRating()) / 100;
        if ($kFactor < 16) {
            $kFactor = 16;
        }
        return $kFactor;
    }

    private function calculateEloChanges(): array
    {
        $winChanceWinner = $this->getWinChanceForPlayers($this->winner, $this->loser);
        $changes = array(
            "winner" => $this->getEloChangeForWin($winChanceWinner),
            "loser" => $this->getEloChangeForLose(1 - $winChanceWinner)
        );
        return $changes;
    }

    private function getWinChanceForPlayers(Player $p1, Player $p2): float
    {
        return $p1->getQpoints() / ($p1->getQpoints() + $p2->getQpoints());
    }

    private function getEloChangeForWin(float $winFactor): int
    {
        return ceil($this->getKFactor() * (1 - $winFactor));
    }

    private function getEloChangeForLose(float $winFactor): int
    {
        return ceil($this->getKFactor() * (0 - $winFactor));
    }

    /**
     * @return array Array of changes
     */
    public function updatePlayers(): array
    {
        $changes = $this->calculateEloChanges();
        $this->winner->setWins($this->winner->getWins() + 1);
        $this->winner->setEloRating($this->winner->getEloRating() + $changes['winner']);

        $this->loser->setLoses($this->loser->getLoses() + 1);
        $this->loser->setEloRating($this->loser->getEloRating() + $changes['loser']);

        return $changes;
    }
}