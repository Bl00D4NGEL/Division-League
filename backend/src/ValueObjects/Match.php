<?php

namespace App\ValueObjects;

use App\Entity\History;
use App\Entity\Proof;
use App\Entity\Team;
use App\ValueObjects\EloCalculator\DefaultEloMultiplier;
use App\ValueObjects\EloCalculator\EloCalculator;
use App\ValueObjects\EloCalculator\SweepEloMultiplier;
use DateTime;

class Match
{
    /** @var Team */
    private $winner;

    /** @var Team */
    private $loser;

    /** @var string[] */
    private $proofUrl;

    /** @var bool */
    private $isSweep = false;

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

    /**
     * @param string[] $proof_url
     * @return Match
     */
    public function setProofUrl(array $proof_url): self
    {
        $this->proofUrl = $proof_url;

        return $this;
    }

    public function setIsSweep(bool $isSweep): self
    {
        $this->isSweep = $isSweep;

        return $this;
    }

    public function execute(): History
    {
        $eloMultiplier = $this->isSweep ? new SweepEloMultiplier() : new DefaultEloMultiplier();
        $eloCalculator = new EloCalculator(
            $this->winner->getAverageElo(),
            $this->loser->getAverageElo(),
            $eloMultiplier
        );

        $this->loser->lose($eloCalculator->getEloChangeForLoser());
        $this->winner->win($eloCalculator->getEloChangeForWinner());

        $history = new History();
        $history
            ->setLoser($this->loser->getId())
            ->setWinner($this->winner->getId())
            ->setLoserGain($eloCalculator->getEloChangeForLoser())
            ->setWinnerGain($eloCalculator->getEloChangeForWinner())
            ->setCreateTime(new DateTime())
            ->setIsSweep($this->isSweep);

        foreach ($this->proofUrl as $proofUrl) {
            $proof = new Proof();
            $proof->setUrl($proofUrl);
            $history->addProof($proof);
        }

        return $history;
    }

}
