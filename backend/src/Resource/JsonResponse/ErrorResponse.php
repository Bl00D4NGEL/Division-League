<?php

namespace App\Resource\JsonResponse;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponse extends JsonResponse
{
    public const INVALID_DATA_SENT = 'Sent data is invalid';
    public const PLAYER_DOES_ALREADY_EXIST = 'Player %s does already exist!';
    public const INVALID_CREDENTIALS_SENT = 'Invalid credentials sent';
    public const ERROR_PERSISTING_DATA = 'Error persisting data';

    public function __construct($message = null, int $status = null, array $headers = [])
    {
        $headers["X-Content-Type-Options"] = 'nosniff';
        $headers['Access-Control-Allow-Origin'] = '*';
        parent::__construct(["status" => "error", "message" => $message], $status ?? Response::HTTP_BAD_REQUEST, $headers);
    }
}
