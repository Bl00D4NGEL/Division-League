<?php

namespace App\Resource\JsonResponse;

use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorResponse extends JsonResponse
{
    public const INVALID_DATA_SENT = 'Sent data is invalid';
    public const PLAYER_DOES_ALREADY_EXIST = 'Player %s does already exist!';
    public const INVALID_CREDENTIALS_SENT = 'Invalid credentials sent';
    public const ERROR_PERSISTING_DATA = 'Error persisting data';

    public function __construct($message = null, int $status = 400, array $headers = [])
    {
        parent::__construct(["status" => "error", "message" => $message], $status, $headers);
    }
}