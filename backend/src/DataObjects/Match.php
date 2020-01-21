<?php

namespace App\DataObjects;

use App\Entity\History;
use App\Entity\Player;
use App\Entity\Team;
use App\Repository\PlayerRepository;
use App\Repository\RosterRepository;
use App\Service\EloCalculator;
use Exception;

class Match
{
    /** @var RosterRepository */
    private $rosterRepository;

    /** @var PlayerRepository */
    private $playerRepository;

    /** @var Team */
    private $winner;

    /** @var Team */
    private $loser;

    /** @var string */
    private $proofUrl;

    /** @var History */
    private $history;

    public function __construct(RosterRepository $rosterRepository, PlayerRepository $playerRepository)
    {
        $this->rosterRepository = $rosterRepository;
        $this->playerRepository = $playerRepository;
    }

    /**
     * @param int[] $playerIds
     * @param string $name (Optional) name of team if it is to be created
     * @return Match
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setWinner(array $playerIds, ?string $name = ''): self
    {
        $winner = $this->rosterRepository->getTeamForPlayers($playerIds);
        if ($winner === null) {
            $winner = $this->rosterRepository->createTeamForPlayers($playerIds, $name);
        }
        $this->winner = $winner;
        return $this;
    }

    /**
     * @param int[] $playerIds
     * @param string $name (Optional) name of team if it is to be created
     * @return Match
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setLoser(array $playerIds, ?string $name = ''): self
    {
        $loser = $this->rosterRepository->getTeamForPlayers($playerIds);
        if ($loser === null) {
            $loser = $this->rosterRepository->createTeamForPlayers($playerIds, $name);
        }
        $this->loser = $loser;

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
     * @return History
     * @throws Exception
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
     * @param array $players
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
