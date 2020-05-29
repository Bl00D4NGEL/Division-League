<?php declare(strict_types=1);

namespace App\Factory\Exceptions;

use InvalidArgumentException;

final class PlayerNotFoundException extends InvalidArgumentException
{
    public function __construct(int $playerId)
    {
        parent::__construct(sprintf('Player with id %d was not found', $playerId));
    }
}
