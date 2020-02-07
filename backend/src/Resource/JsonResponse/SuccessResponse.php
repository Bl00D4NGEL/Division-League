<?php

namespace App\Resource\JsonResponse;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SuccessResponse extends JsonResponse
{
    public function __construct($data = null, int $status = Response::HTTP_OK, array $headers = [])
    {
        $headers["X-Content-Type-Options"] = 'nosniff';
        $headers['Access-Control-Allow-Origin'] = '*';
        parent::__construct(["status" => "success", "data" => $data], $status, $headers);
    }
}
