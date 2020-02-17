<?php

namespace App\ValueObjects;

use App\Entity\History;
use App\Entity\Team;
use DateTime;

class Match
{
    /** @var Team */
    private $winner;

    /** @var Team */
    private $loser;

    /** @var string */
    private $proofUrl;

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

    public function execute(): History
    {
        $eloCalculator = new EloCalculator(
            $this->winner->getAverageElo(),
            $this->loser->getAverageElo()
        );

        $this->loser->lose($eloCalculator->getEloChangeForLoser());
        $this->winner->win($eloCalculator->getEloChangeForWinner());

        $history = new History();
        $history
            ->setLoser($this->loser->getId())
            ->setWinner($this->winner->getId())
            ->setLoserGain($eloCalculator->getEloChangeForLoser())
            ->setWinnerGain($eloCalculator->getEloChangeForWinner())
            ->setProofUrl($this->proofUrl)
            ->setCreateTime(new DateTime());
        return $history;
    }
}
