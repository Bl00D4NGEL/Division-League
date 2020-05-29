<?php declare(strict_types=1);

namespace App\ValueObjects\Validator\EloValidator;

final class EloDifferenceValidator
{
    private const MAX_ELO_DIFFERENCE = 300;

    /**
     * @param int[] $elos
     */
    public function validate(array $elos): void {
        if ((max($elos) - min($elos)) > self::MAX_ELO_DIFFERENCE) {
            throw new EloDifferenceTooBigException();
        }
    }
}
