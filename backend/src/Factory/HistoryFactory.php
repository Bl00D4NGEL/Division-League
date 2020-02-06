<?php


namespace App\Factory;


use App\Repository\HistoryRepository;
use App\ValueObjects\RichHistory;

class HistoryFactory
{
    /** @var HistoryRepository */
    private $historyRepository;
    /** @var TeamFactory */
    private $teamFactory;

    public function __construct(HistoryRepository $historyRepository, TeamFactory $teamFactory)
    {
        $this->historyRepository = $historyRepository;
        $this->teamFactory = $teamFactory;
    }

    public function createFromId(int $historyId): ?RichHistory {
        $history = $this->historyRepository->find($historyId);
        if (null === $history) {
            return null;
        }

        $richHistory = new RichHistory();
        $richHistory->setHistory($history);
        $richHistory->setLoserObject($this->teamFactory->createFromId($history->getLoser()));
        $richHistory->setWinnerObject($this->teamFactory->createFromId($history->getWinner()));
        return $richHistory;
    }
}
