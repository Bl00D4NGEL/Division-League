<?php declare(strict_types=1);

namespace App\ValueObjects\Validator\EloValidator;

use InvalidArgumentException;

final class EloDifferenceTooBigException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Elo difference is too big');
    }
}
