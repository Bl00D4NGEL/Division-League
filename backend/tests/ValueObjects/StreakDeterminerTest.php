<?php declare(strict_types=1);

namespace App\Tests\ValueObjects;

use App\Entity\Player;
use App\Repository\ParticipantRepository;
use App\ValueObjects\StreakDeterminer;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class StreakDeterminerTest extends TestCase
{
    public function testDeterminerShouldReturnZeroOnEmptyRepository(): void {
        /** @var MockObject|ParticipantRepository $participantRepository */
        $participantRepository = $this->createMock(ParticipantRepository::class);
        $participantRepository->method('getHistoryTimesForPlayer')->willReturn([]);
        $streakDeterminer = new StreakDeterminer($participantRepository, new DateTimeImmutable());

        static::assertSame(0, $streakDeterminer->getStreakLengthForPlayer(new Player()));
    }

    public function testDeterminerShouldReturnOneIfStreakOfOneWeekExists(): void {
        // 25th May 2020 = Monday
        $dateTime = new DateTimeImmutable('2020-05-25');

        // 1st June 2020 = Monday
        $startTime = new DateTimeImmutable('2020-06-01');
        /** @var MockObject|ParticipantRepository $participantRepository */
        $participantRepository = $this->createMock(ParticipantRepository::class);
        $participantRepository->method('getHistoryTimesForPlayer')->willReturn([
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
        ]);

        $streakDeterminer = new StreakDeterminer($participantRepository, $startTime);

        static::assertSame(1, $streakDeterminer->getStreakLengthForPlayer(new Player()));
    }

    public function testDeterminerShouldReturnOneIfStreakIsBrokenInBetween(): void {
        // 25th May 2020 = Monday
        $dateTime = new DateTimeImmutable('2020-05-25');

        $twoWeeksEarlier = new DateTimeImmutable('2020-05-11');

        // 1st June 2020 = Monday
        $startTime = new DateTimeImmutable('2020-06-01');
        /** @var MockObject|ParticipantRepository $participantRepository */
        $participantRepository = $this->createMock(ParticipantRepository::class);
        $participantRepository->method('getHistoryTimesForPlayer')->willReturn([
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
        ]);

        $streakDeterminer = new StreakDeterminer($participantRepository, $startTime);

        static::assertSame(1, $streakDeterminer->getStreakLengthForPlayer(new Player()));
    }

    public function testDeterminerShouldReturnCorrectStreakLengthOnMultipleWeeks(): void {

        // 25th May 2020 = Monday
        $dateTime = new DateTimeImmutable('2020-05-25');

        $oneWeekEarlier = new DateTimeImmutable('2020-05-18');
        $twoWeeksEarlier = new DateTimeImmutable('2020-05-11');

        // 1st June 2020 = Monday
        $startTime = new DateTimeImmutable('2020-06-01');
        /** @var MockObject|ParticipantRepository $participantRepository */
        $participantRepository = $this->createMock(ParticipantRepository::class);
        $participantRepository->method('getHistoryTimesForPlayer')->willReturn([
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $dateTime,
            $oneWeekEarlier,
            $oneWeekEarlier,
            $oneWeekEarlier,
            $oneWeekEarlier,
            $oneWeekEarlier,
            $oneWeekEarlier,
            $oneWeekEarlier,
            $dateTime,
            $dateTime,
            $dateTime,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
            $twoWeeksEarlier,
        ]);

        $streakDeterminer = new StreakDeterminer($participantRepository, $startTime);

        static::assertSame(3, $streakDeterminer->getStreakLengthForPlayer(new Player()));
    }
}
