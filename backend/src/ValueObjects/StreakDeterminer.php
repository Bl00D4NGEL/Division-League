<?php declare(strict_types=1);

namespace App\ValueObjects;

use App\Entity\Player;
use App\Repository\ParticipantRepository;
use App\ValueObjects\Grouper\DateTime\DateTimeGrouperInterface;
use DateTimeImmutable;
use DateTimeInterface;

final class StreakDeterminer
{
    private const GAMES_REQUIRED_TO_KEEP_STREAK = 7;

    private ParticipantRepository $participantRepository;
    private DateTimeInterface $startingTime;
    private DateTimeGrouperInterface $dateTimeGrouperYearWeek;

    public function __construct(ParticipantRepository $participantRepository, DateTimeGrouperInterface $dateTimeGrouperYearWeek, DateTimeImmutable $startingTime = null)
    {
        $this->participantRepository = $participantRepository;
        $this->startingTime = $startingTime ?? new DateTimeImmutable();
        $this->dateTimeGrouperYearWeek = $dateTimeGrouperYearWeek;
    }

    public function getStreakLengthForPlayer(Player $player): int
    {
        $grouped = $this->dateTimeGrouperYearWeek->group(
            $this->participantRepository->getHistoryTimesForPlayer($player)
        );

        $onStreak = true;
        $streakCount = 0;
        $start = $this->startingTime->modify('-1 week');
        while ($onStreak) {
            if (self::GAMES_REQUIRED_TO_KEEP_STREAK <= ($grouped[$start->format($this->dateTimeGrouperYearWeek->getGroupByKey())] ?? 0)) {
                $streakCount++;
                $start = $start->modify('-1 week');
            } else {
                $onStreak = false;
            }
        }

        return $streakCount;
    }
}
