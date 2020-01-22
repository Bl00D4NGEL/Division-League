<?php

namespace App\ValueObjects;

use App\Entity\History;
use App\Entity\Player;
use App\Entity\Team;
use App\Service\EloCalculator;
use Exception;

class Match
{
    /** @var Team */
    private $winner;

    /** @var Team */
    private $loser;

    /** @var string */
    private $proofUrl;

    /** @var History */
    private $history;

    /**
     * @param Team $team
     * @return Match
     */
    public function setWinner(Team $team): self
    {
        $this->winner = $team;

        return $this;
    }

    /**
     * @param Team $team
     * @return Match
     */
    public function setLoser(Team $team): self
    {
        $this->loser = $team;

        return $this;
    }

    public function getWinner(): ?Team
    {
        return $this->winner;
    }

    public function getLoser(): ?Team
    {
        return $this->loser;
    }

    public function setProofUrl(string $proof_url): self
    {
        $this->proofUrl = $proof_url;

        return $this;
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $eloCalculator = new EloCalculator(
            $this->getAverageEloForPlayers($this->winner->getPlayers()),
            $this->getAverageEloForPlayers($this->loser->getPlayers())
        );

        $this->loser->lose($eloCalculator->getEloChangeForLoser());
        $this->winner->win($eloCalculator->getEloChangeForWinner());

        $this->history = new History();
        $this->history
            ->setLoser($this->loser->getId())
            ->setWinner($this->winner->getId())
            ->setLoserGain($eloCalculator->getEloChangeForLoser())
            ->setWinnerGain($eloCalculator->getEloChangeForWinner())
            ->setProofUrl($this->proofUrl);
    }

    /**
     * @param Player[] $players
     * @return int
     */
    private function getAverageEloForPlayers(array $players): int
    {
        return ceil(
            array_sum(
                array_map(function (Player $val) {
                    return $val->getElo();
                }, $players)
            ) / count($players)
        );
    }

    /**
     * @return History
     * @throws Exception
     */
    public function getHistory(): History
    {
        if ($this->history === null) {
            throw new Exception('Execute function hasn\'t generated a history yet. Please call `execute` before this.');
        }
        return $this->history;
    }
}
