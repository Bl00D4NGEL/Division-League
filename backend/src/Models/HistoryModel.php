<?php

namespace App\Models;

use App\Entity\History;
use App\Entity\Player;
use App\Entity\Proof;
use App\Entity\Team;
use App\Factory\RosterFactory;
use App\Factory\TeamFactory;
use App\Repository\HistoryRepository;
use App\Resource\AddHistoryRequest;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use App\ValueObjects\EloCalculator\DefaultEloMultiplier;
use App\ValueObjects\EloCalculator\EloCalculator;
use App\ValueObjects\EloCalculator\SweepEloMultiplier;
use App\ValueObjects\HistoryFormatter;
use App\ValueObjects\Match\Match;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;


class HistoryModel
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var HistoryRepository */
    private $historyRepository;

    /** @var HistoryFormatter */
    private $historyFormatter;

    /** @var TeamFactory */
    private $teamFactory;

    /** @var RosterFactory */
    private $rosterFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        HistoryRepository $historyRepository,
        HistoryFormatter $historyFormatter,
        TeamFactory $teamFactory,
        RosterFactory $rosterFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->historyRepository = $historyRepository;
        $this->historyFormatter = $historyFormatter;
        $this->teamFactory = $teamFactory;
        $this->rosterFactory = $rosterFactory;
    }

    /**
     * @param AddHistoryRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function addHistory(AddHistoryRequest $request): JsonResponse
    {
        if (!$request->isValid()) {
            return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        }

        $winner = $this->teamFactory->createTeamFromPlayerIds($request->winner);
        if (!empty($request->winnerTeamName)) {
            $winner->setName($request->winnerTeamName);
        }

        $loser = $this->teamFactory->createTeamFromPlayerIds($request->loser);
        if (!empty($request->loserTeamName)) {
            $loser->setName($request->loserTeamName);
        }

        if (!$winner->isPlayerEloDifferenceValid() || !$loser->isPlayerEloDifferenceValid()) {
            return new ErrorResponse(ErrorResponse::ELO_DIFFERENCE_TOO_BIG);
        }

        $this->entityManager->persist($winner);
        $this->entityManager->persist($loser);

        $this->entityManager->flush();

        $match = new Match();

        $matchResult = $match->play(
            $this->createMatchTeamFromEntityTeam($winner),
            $this->createMatchTeamFromEntityTeam($loser),
            new EloCalculator($request->isSweep ? new SweepEloMultiplier() : new DefaultEloMultiplier())
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
        return new SuccessResponse($this->historyFormatter->format([$history]));
    }

    public function createMatchTeamFromEntityTeam(Team $team): \App\ValueObjects\Match\Team
    {
        return new \App\ValueObjects\Match\Team(
            array_map(static function (Player $player) {
                return new \App\ValueObjects\Match\Player($player->getElo());
            }, $team->getPlayers())
        );
    }

    /**
     * @param Team $team
     * @throws Exception
     */
    private function createAndPersistRostersForTeam(Team $team): void
    {
        foreach ($this->rosterFactory->createRostersFromTeamAndPlayers($team, $team->getPlayers()) as $roster) {
            $this->entityManager->persist($roster);
        }
    }

    public function getHistoryRecent(): JsonResponse
    {
        return new SuccessResponse($this->historyFormatter->format($this->historyRepository->findLastEntries(35)));
    }

    public function getHistoryAll(): JsonResponse
    {
        return new SuccessResponse($this->historyFormatter->format($this->historyRepository->findAll()));
    }
}
