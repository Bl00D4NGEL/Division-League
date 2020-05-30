<?php declare(strict_types=1);

namespace App\ValueObjects\Grouper\DateTime;

use DateTimeInterface;

final class DateTimeGrouperYearWeek implements DateTimeGrouperInterface
{
    private const GROUP_BY_DATE_FORMAT = 'Y-W';

    /**
     * @param DateTimeInterface[] $dates
     * @return DateTimeInterface[]
     */
    public function group(array $dates): array
    {
        $grouped = [];

        foreach ($dates as $date) {
            if (!isset($grouped[$date->format(self::GROUP_BY_DATE_FORMAT)])) {
                $grouped[$date->format(self::GROUP_BY_DATE_FORMAT)] = 0;
            }

            $grouped[$date->format(self::GROUP_BY_DATE_FORMAT)]++;
        }

        return $grouped;
    }

    public function getGroupByKey(): string
    {
        return self::GROUP_BY_DATE_FORMAT;
    }
}
