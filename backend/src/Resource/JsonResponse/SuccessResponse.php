<?php

namespace App\Resource\JsonResponse;

use Symfony\Component\HttpFoundation\JsonResponse;

class SuccessResponse extends JsonResponse
{
    public function __construct($data = null, int $status = 200, array $headers = [])
    {
        parent::__construct(["status" => "success", "data" => $data], $status, $headers);
    }
}