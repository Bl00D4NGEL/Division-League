<?php

namespace App\ValueObjects;

use App\Entity\History;
use App\Entity\Player;
use App\Entity\Proof;
use App\Factory\HistoryFactory;

class HistoryFormatter
{
    /** @var HistoryFactory */
    private $historyFactory;

    public function __construct(HistoryFactory $historyFactory)
    {
        $this->historyFactory = $historyFactory;
    }

    /**
     * @param History[] $histories
     * @return array
     */
    public function format(array $histories): array
    {
        $historyData = [];
        foreach ($histories as $history) {
            $richHistory = $this->historyFactory->createFromId($history->getId());
            $winnerTeam = $richHistory->getWinnerObject();
            $loserTeam = $richHistory->getLoserObject();
            $historyData[] = [
                "winner" => $this->mapPlayerArray($winnerTeam->getPlayers()),
                "loser" => $this->mapPlayerArray($loserTeam->getPlayers()),
                "proofs" => array_map(static function (Proof $proof) {
                    return $proof->getUrl();
                }, $history->getProof()->getValues()),
                "winnerTeamName" => $winnerTeam->getName(),
                "loserTeamName" => $loserTeam->getName(),
                "winnerEloWin" => $history->getWinnerGain(),
                "loserEloLose" => $history->getLoserGain(),
                "creationTime" => $history->getCreateTime()->getTimestamp(),
                "isSweep" => $history->getIsSweep(),
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
