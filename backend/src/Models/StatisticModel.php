<?php

namespace App\Models;

use App\Factory\HistoryFactory;
use App\Repository\HistoryRepository;
use App\Resource\JsonResponse\SuccessResponse;
use App\ValueObjects\GameCollection;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatisticModel
{
    /** @var HistoryFactory */
    private $historyFactory;

    /** @var HistoryRepository */
    private $historyRepository;

    /** @var GameCollection */
    private $gameCollection;

    public function __construct(HistoryFactory $historyFactory, HistoryRepository $historyRepository)
    {
        $this->historyFactory = $historyFactory;
        $this->historyRepository = $historyRepository;
        $this->gameCollection = new GameCollection();
    }

    public function getGamesPerWeek(): JsonResponse
    {
        foreach ($this->historyRepository->findAll() as $history) {
            $this->gameCollection->processRichHistory(
                $this->historyFactory->createFromId($history->getId())
            );
        }
        return new SuccessResponse($this->gameCollection->export());
    }
}
