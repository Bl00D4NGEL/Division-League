<?php

namespace App\Models;

use App\Entity\History;
use App\Entity\Participant;
use App\Entity\Player;
use App\Entity\Proof;
use App\Factory\Exceptions\PlayerNotFoundException;
use App\Factory\RosterFactory;
use App\Factory\TeamFactory;
use App\Repository\PlayerRepository;
use App\Resource\AddHistoryRequest;
use App\Resource\InvalidRequestException;
use App\ValueObjects\EloCalculator\EloCalculator;
use App\ValueObjects\EloMultiplier\DefaultEloMultiplier;
use App\ValueObjects\EloMultiplier\StreakEloMultiplier;
use App\ValueObjects\EloMultiplier\SweepEloMultiplier;
use App\ValueObjects\Match\Match;
use App\ValueObjects\Match\Team;
use App\ValueObjects\StreakDeterminer;
use App\ValueObjects\Validator\EloValidator\EloDifferenceValidator;
use Doctrine\ORM\EntityManagerInterface;


class HistoryModel
{
    private EntityManagerInterface $entityManager;
    private TeamFactory $teamFactory;
    private RosterFactory $rosterFactory;
    private EloDifferenceValidator $eloDifferenceValidator;
    private PlayerRepository $playerRepository;
    private StreakDeterminer $streakDeterminer;

    public function __construct(
        EntityManagerInterface $entityManager,
        TeamFactory $teamFactory,
        RosterFactory $rosterFactory,
        EloDifferenceValidator $eloDifferenceValidator,
        PlayerRepository $playerRepository,
        StreakDeterminer $streakDeterminer
    )
    {
        $this->entityManager = $entityManager;
        $this->teamFactory = $teamFactory;
        $this->rosterFactory = $rosterFactory;
        $this->eloDifferenceValidator = $eloDifferenceValidator;
        $this->playerRepository = $playerRepository;
        $this->streakDeterminer = $streakDeterminer;
    }

    public function addHistory(AddHistoryRequest $request): History
    {
        if (!$request->isValid()) {
            throw new InvalidRequestException();
        }

        $winners = $this->getPlayerEntitiesFromIds($request->winner);
        $this->validatePlayers($winners);

        $losers = $this->getPlayerEntitiesFromIds($request->loser);
        $this->validatePlayers($losers);

        $match = new Match();

        $matchResult = $match->play(
            $this->createTeamFromPlayers($winners),
            $this->createTeamFromPlayers($losers),
            new EloCalculator()
        );

        $history = new History();
        $history
            ->setCreationTime($matchResult->creationTime())
            ->setIsSweep($request->isSweep);

        $this->addProofsToHistory($request->proofUrl, $history);

        $baseMultiplier = $request->isSweep ? new SweepEloMultiplier() : new DefaultEloMultiplier();
        foreach ($winners as $winner) {
            $eloMultiplier = new StreakEloMultiplier(
                $baseMultiplier,
                $this->streakDeterminer->getStreakLengthForPlayer($winner)
            );
            $winner->setElo($winner->getElo() + $matchResult->eloChange() * $eloMultiplier->getWinFactor());
            $winner->setWins($winner->getWins() + 1);
            $participant = new Participant();
            $participant
                ->setEloChange($matchResult->eloChange() * $eloMultiplier->getWinFactor())
                ->setPlayer($winner);

            $history->addParticipant($participant);
        }

        foreach ($losers as $loser) {
            $eloMultiplier = new StreakEloMultiplier(
                $baseMultiplier,
                $this->streakDeterminer->getStreakLengthForPlayer($loser)
            );
            $loser->setElo($loser->getElo() - $matchResult->eloChange() * $eloMultiplier->getLoseFactor());
            $loser->setLoses($loser->getLoses() + 1);

            $participant = new Participant();
            $participant
                ->setEloChange($matchResult->eloChange() * $eloMultiplier->getWinFactor() * -1)
                ->setPlayer($loser);

            $history->addParticipant($participant);
        }

        $this->entityManager->persist($history);
//
//        $this->createAndPersistRostersForTeam($winner);
//        $this->createAndPersistRostersForTeam($loser);

        $this->entityManager->flush();
        return $history;
    }

    /**
     * @param int[] $playerIds
     * @return Player[]
     */
    private function getPlayerEntitiesFromIds(array $playerIds): array
    {
        $players = [];
        foreach ($playerIds as $playerId) {
            $player = $this->playerRepository->find($playerId);
            if (null === $player) {
                throw new PlayerNotFoundException($playerId);
            }
            $players[] = $player;
        }

        return $players;
    }

    /**
     * @param Player[] $players
     */
    private function validatePlayers(array $players): void
    {
        $this->eloDifferenceValidator->validate(
            $this->getEloFromPlayersOfTeam(
                $players
            )
        );
    }

    /**
     * @param Player[] $players
     * @return int[]
     */
    private function getEloFromPlayersOfTeam(array $players): array
    {
        return array_map(function (Player $player) {
            return $player->getElo();
        }, $players);
    }

    /**
     * @param Player[] $players
     * @return Team
     */
    private function createTeamFromPlayers(array $players): Team
    {
        return new Team(
            array_map(static function (Player $player) {
                return new \App\ValueObjects\Match\Player($player->getElo());
            }, $players)
        );
    }

    /**
     * @param string[] $proofUrls
     * @param History $history
     */
    private function addProofsToHistory(array $proofUrls, History $history): void
    {
        foreach ($proofUrls as $proofUrl) {
            $proof = new Proof();
            $proof->setUrl($proofUrl);
            $history->addProof($proof);
        }
    }
}
