<?php declare(strict_types=1);

namespace App\Resource;

use InvalidArgumentException;

final class InvalidRequestException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Sent data is invalid');
    }
}
