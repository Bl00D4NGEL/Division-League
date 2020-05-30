<?php declare(strict_types=1);

namespace App\ValueObjects\Grouper\DateTime;

use DateTimeInterface;

interface DateTimeGrouperInterface
{
    /**
     * @param DateTimeInterface[] $dates
     * @return DateTimeInterface[]
     */
    public function group(array $dates): array;

    public function getGroupByKey(): string;
}
