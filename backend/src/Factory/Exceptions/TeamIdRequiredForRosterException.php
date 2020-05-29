<?php declare(strict_types=1);

namespace App\Factory\Exceptions;

use InvalidArgumentException;

final class TeamIdRequiredForRosterException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Team needs to have an id for a roster to be able to be created');
    }
}
