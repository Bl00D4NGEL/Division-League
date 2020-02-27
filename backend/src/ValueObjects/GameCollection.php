<?php

namespace App\ValueObjects;

use App\Entity\Player;
use DateInterval;
use DateTime;
use Exception;

class GameCollection
{
    private const Y_M_D = 'Y-m-d';

    /** @var array */
    private $games = [];

    /**
     * @param RichHistory $richHistory
     * @throws Exception
     */
    public function processRichHistory(RichHistory $richHistory): void {
        $this->incrementPlayedGamesForMultiplePlayers($richHistory->getWinnerObject()->getPlayers(), $richHistory->getHistory()->getCreateTime());
        $this->incrementPlayedGamesForMultiplePlayers($richHistory->getLoserObject()->getPlayers(), $richHistory->getHistory()->getCreateTime());
    }

    /**
     * @param Player[] $players
     * @param DateTime $gameDate
     * @return void
     * @throws Exception
     */
    public function incrementPlayedGamesForMultiplePlayers(array $players, DateTime $gameDate): void {
        foreach ($players as $player) {
            $this->incrementPlayedGamesForPlayerName($player->getName(), $gameDate);
        }
    }

    /**
     * @throws Exception
     */
    public function incrementPlayedGamesForPlayerName(string $playerName, DateTime $gameDate): void {
        $dateKey = $this->getDateKey($gameDate);
        if (!isset($this->games[$playerName])) {
            $this->games[$playerName] = [];
        }
        if (!isset($this->games[$playerName][$dateKey])) {
            $this->games[$playerName][$dateKey] = 0;
        }
        $this->games[$playerName][$dateKey]++;
    }

    public function export(): array {
        return $this->games;
    }

    /**
     * @throws Exception
     */
    private function getDateKey(DateTime $gameDate): string
    {
        $dow = $gameDate->format('N');
        $from = clone $gameDate;
        if ($dow > 1) {
            $fromDiff = new DateInterval('P' . ($dow - 1) . 'D');
            $from->sub($fromDiff);
        }

        $to = clone $gameDate;
        if ($dow < 7) {
            $toDiff = new DateInterval('P' . (7 - $dow) . 'D');
            $to->add($toDiff);
        }

        return $from->format(self::Y_M_D) . ':' . $to->format(self::Y_M_D);
    }
}
