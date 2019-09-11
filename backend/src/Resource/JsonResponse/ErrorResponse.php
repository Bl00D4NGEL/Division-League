<?php

namespace App\Resource\JsonResponse;

use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorResponse extends JsonResponse
{
    public function __construct($message = null, int $status = 400, array $headers = [])
    {
        parent::__construct(["status" => "error", "message" => $message], $status, $headers);
    }
}