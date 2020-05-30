<?php declare(strict_types=1);

namespace App\ValueObjects;

use App\Entity\Player;
use App\Repository\ParticipantRepository;
use DateTimeImmutable;
use DateTimeInterface;

final class StreakDeterminer
{
    private const GROUP_BY_DATE_FORMAT = 'Y-W';
    private const GAMES_REQUIRED_TO_KEEP_STREAK = 7;

    private ParticipantRepository $participantRepository;
    private DateTimeInterface $startingTime;

    public function __construct(ParticipantRepository $participantRepository, DateTimeImmutable $startingTime = null)
    {
        $this->participantRepository = $participantRepository;
        $this->startingTime = $startingTime ?? new DateTimeImmutable();
    }

    public function getStreakLengthForPlayer(Player $player): int
    {
        $groupedByWeek = $this->getDatesGroupedByFormat(
            $this->participantRepository->getHistoryTimesForPlayer($player),
            self::GROUP_BY_DATE_FORMAT
        );

        $onStreak = true;
        $streakCount = 0;
        $start = $this->startingTime->modify('-1 week');
        while ($onStreak) {
            if (self::GAMES_REQUIRED_TO_KEEP_STREAK <= ($groupedByWeek[$start->format(self::GROUP_BY_DATE_FORMAT)] ?? 0)) {
                $streakCount++;
                $start = $start->modify('-1 week');
            } else {
                $onStreak = false;
            }
        }

        return $streakCount;
    }

    /**
     * @param DateTimeInterface[] $dates
     * @param string $format
     * @return array
     */
    private function getDatesGroupedByFormat(array $dates, string $format): array
    {
        $grouped = [];

        foreach ($dates as $date) {
            if (!isset($grouped[$date->format($format)])) {
                $grouped[$date->format($format)] = 0;
            }

            $grouped[$date->format($format)]++;
        }
        return $grouped;
    }
}
