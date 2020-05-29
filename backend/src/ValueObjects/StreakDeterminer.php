<?php declare(strict_types=1);

namespace App\ValueObjects;

use App\Entity\Player;

final class StreakDeterminer
{
    public function getStreakLengthForPlayer(Player $player): int {
        return 0;
    }
}
