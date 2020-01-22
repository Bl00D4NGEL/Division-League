<?php

namespace App\Models;

use App\ValueObjects\HistoryFormatter;
use App\Repository\HistoryRepository;
use App\Resource\AddHistoryRequest;
use App\Resource\GetHistoryRequest;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
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

    public function __construct(
        EntityManagerInterface $entityManager,
        HistoryRepository $historyRepository,
        HistoryFormatter $historyFormatter
    )
    {
        $this->entityManager = $entityManager;
        $this->historyRepository = $historyRepository;
        $this->historyFormatter = $historyFormatter;
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

        $match = $this->historyRepository->createMatchFrom($request);
        $match->execute();

        $this->entityManager->persist($match->getHistory());
        $this->entityManager->persist($match->getWinner());
        $this->entityManager->persist($match->getLoser());
        $this->entityManager->flush();
        return new SuccessResponse($this->historyFormatter->format([$match->getHistory()]));
    }

    public function getHistoryRecent(): JsonResponse
    {
        return new SuccessResponse($this->historyFormatter->format($this->historyRepository->findLastEntries(20)));
    }

    /**
     * @param GetHistoryRequest $request
     * @return JsonResponse
     */
    public function getHistory(GetHistoryRequest $request): JsonResponse
    {
        return new SuccessResponse($this->historyFormatter->format($this->historyRepository->findWithHistoryEntity($request->history, $request->limit)));
    }


}
