<?php

namespace App\Models;

use App\Entity\Team;
use App\Factory\RosterFactory;
use App\Factory\TeamFactory;
use App\ValueObjects\HistoryFormatter;
use App\Repository\HistoryRepository;
use App\Resource\AddHistoryRequest;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use App\ValueObjects\Match;
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
        $this->entityManager->persist($winner);

        $loser = $this->teamFactory->createTeamFromPlayerIds($request->loser);
        if (!empty($request->loserTeamName)) {
            $loser->setName($request->loserTeamName);
        }
        $this->entityManager->persist($loser);

        $this->entityManager->flush();

        $match = new Match();
        $match->setWinner($winner);
        $match->setLoser($loser);
        $match->setProofUrl($request->proofUrl);
        $history = $match->execute();

        $this->entityManager->persist($history);

        $this->createAndPersistRostersForTeam($winner);
        $this->createAndPersistRostersForTeam($loser);


        $this->entityManager->flush();
        return new SuccessResponse($this->historyFormatter->format([$history]));
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
        return new SuccessResponse($this->historyFormatter->format($this->historyRepository->findLastEntries(20)));
    }
}
