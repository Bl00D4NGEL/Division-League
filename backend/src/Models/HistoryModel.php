<?php

namespace App\Models;

use App\Entity\History;
use App\Entity\Player;
use App\Entity\Proof;
use App\Entity\Team;
use App\Factory\RosterFactory;
use App\Factory\TeamFactory;
use App\Resource\AddHistoryRequest;
use App\Resource\InvalidRequestException;
use App\ValueObjects\EloCalculator\DefaultEloMultiplier;
use App\ValueObjects\EloCalculator\EloCalculator;
use App\ValueObjects\EloCalculator\StreakEloMultiplier;
use App\ValueObjects\EloCalculator\SweepEloMultiplier;
use App\ValueObjects\Match\Match;
use App\ValueObjects\Validator\EloValidator\EloDifferenceValidator;
use Doctrine\ORM\EntityManagerInterface;


class HistoryModel
{
    private EntityManagerInterface $entityManager;
    private TeamFactory $teamFactory;
    private RosterFactory $rosterFactory;
    private EloDifferenceValidator $eloDifferenceValidator;

    public function __construct(
        EntityManagerInterface $entityManager,
        TeamFactory $teamFactory,
        RosterFactory $rosterFactory,
        EloDifferenceValidator $eloDifferenceValidator
    )
    {
        $this->entityManager = $entityManager;
        $this->teamFactory = $teamFactory;
        $this->rosterFactory = $rosterFactory;
        $this->eloDifferenceValidator = $eloDifferenceValidator;
    }

    public function addHistory(AddHistoryRequest $request): History
    {
        if (!$request->isValid()) {
            throw new InvalidRequestException();
        }

        $winner = $this->teamFactory->createTeamFromPlayerIds($request->winner);
        if (!empty($request->winnerTeamName)) {
            $winner->setName($request->winnerTeamName);
        }
        $this->eloDifferenceValidator->validate($this->getEloFromPlayersOfTeam($winner));

        $loser = $this->teamFactory->createTeamFromPlayerIds($request->loser);
        if (!empty($request->loserTeamName)) {
            $loser->setName($request->loserTeamName);
        }
        $this->eloDifferenceValidator->validate($this->getEloFromPlayersOfTeam($loser));

        $this->entityManager->persist($winner);
        $this->entityManager->persist($loser);

        $this->entityManager->flush();

        $match = new Match();

        $matchResult = $match->play(
            $this->createMatchTeamFromEntityTeam($winner),
            $this->createMatchTeamFromEntityTeam($loser),
            new EloCalculator(
                new StreakEloMultiplier(
                    $request->isSweep ? new SweepEloMultiplier() : new DefaultEloMultiplier(),
                    0
                )
            )
        );

        $history = new History();
        $history
            ->setLoser($loser->getId())
            ->setWinner($winner->getId())
            ->setLoserGain($matchResult->loserLoss())
            ->setWinnerGain($matchResult->winnerGain())
            ->setCreateTime($matchResult->creationTime())
            ->setIsSweep($request->isSweep);

        foreach ($request->proofUrl as $proofUrl) {
            $proof = new Proof();
            $proof->setUrl($proofUrl);
            $history->addProof($proof);
        }

        $this->entityManager->persist($history);

        $this->createAndPersistRostersForTeam($winner);
        $this->createAndPersistRostersForTeam($loser);

        $this->entityManager->flush();
        return $history;
    }

    /**
     * @param Team $team
     * @return int[]
     */
    private function getEloFromPlayersOfTeam(Team $team): array
    {
        return array_map(function (Player $player) {
            return $player->getElo();
        }, $team->getPlayers());
    }

    private function createMatchTeamFromEntityTeam(Team $team): \App\ValueObjects\Match\Team
    {
        return new \App\ValueObjects\Match\Team(
            array_map(static function (Player $player) {
                return new \App\ValueObjects\Match\Player($player->getElo());
            }, $team->getPlayers())
        );
    }

    /**
     * @param Team $team
     */
    private function createAndPersistRostersForTeam(Team $team): void
    {
        foreach ($this->rosterFactory->createRostersFromTeamAndPlayers($team, $team->getPlayers()) as $roster) {
            $this->entityManager->persist($roster);
        }
    }
}
