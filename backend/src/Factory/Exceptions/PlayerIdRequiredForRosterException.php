<?php declare(strict_types=1);

namespace App\Factory\Exceptions;

use InvalidArgumentException;

final class PlayerIdRequiredForRosterException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Player needs to have an id for a roster to be able to be created');
    }
}
