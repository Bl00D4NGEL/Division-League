<?php

namespace App\ValueObjects;

use App\Entity\History;
use App\Entity\Player;
use App\Factory\TeamFactory;

class HistoryFormatter
{
    /** @var TeamFactory */
    private $teamFactory;

    public function __construct(TeamFactory $teamFactory)
    {
        $this->teamFactory = $teamFactory;
    }

    /**
     * @param History[] $histories
     * @return array
     */
    public function format(array $histories): array
    {
        $historyData = [];
        foreach ($histories as $history) {
            $winnerTeam = $this->teamFactory->createFromId($history->getWinner());
            $loserTeam = $this->teamFactory->createFromId($history->getLoser());
            $historyData[] = [
                "winner" => $this->mapPlayerArray($winnerTeam->getPlayers()),
                "loser" => $this->mapPlayerArray($loserTeam->getPlayers()),
                "proofUrl" => $history->getProofUrl(),
                "winnerTeamName" => $winnerTeam->getName(),
                "loserTeamName" => $loserTeam->getName(),
                "winnerEloWin" => $history->getWinnerGain(),
                "loserEloLose" => $history->getLoserGain(),
                "creationTime" => $history->getCreateTime()->getTimestamp(),
                "id" => $history->getId(),
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
