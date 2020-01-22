<?php

namespace App\ValueObjects;

use App\Entity\History;
use App\Entity\Player;
use App\Repository\RosterRepository;
use App\Repository\TeamRepository;

class HistoryFormatter
{
    /** @var RosterRepository */
    private $rosterRepository;

    /** @var TeamRepository */
    private $teamRepository;

    public function __construct(
        RosterRepository $rosterRepository,
        TeamRepository $teamRepository)
    {
        $this->rosterRepository = $rosterRepository;
        $this->teamRepository = $teamRepository;
    }

    /**
     * @param History[] $histories
     * @return array
     */
    public function format(array $histories): array
    {
        $historyData = [];
        foreach ($histories as $history) {
            $historyData[] = [
                "winner" => $this->mapPlayerArray($this->rosterRepository->getPlayersForTeam($history->getWinner())),
                "loser" => $this->mapPlayerArray($this->rosterRepository->getPlayersForTeam($history->getLoser())),
                "proofUrl" => $history->getProofUrl(),
                "winnerTeamName" => $this->teamRepository->getTeamName($history->getWinner()),
                "loserTeamName" => $this->teamRepository->getTeamName($history->getLoser()),
                "winnerEloWin" => $history->getWinnerGain(),
                "loserEloLose" => $history->getLoserGain(),
                "id" => $history->getId()
            ];
        }
        return $historyData;
    }

    private function mapPlayerArray(array $players)
    {
        return array_map(static function (Player $player) {
            return $player->asArray();
        }, $players);
    }
}
