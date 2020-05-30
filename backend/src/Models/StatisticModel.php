<?php

namespace App\Models;

use App\Resource\JsonResponse\SuccessResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatisticModel
{
    public function getGamesPerWeek(): JsonResponse
    {
        // TODO re-implement
        return new SuccessResponse([]);
    }
}
