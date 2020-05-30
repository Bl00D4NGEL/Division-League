<?php

namespace App\Controller;

use App\Models\StatisticModel;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StatisticController extends AbstractController
{
    private SerializerInterface $serializer;
    private StatisticModel $statisticModel;

    public function __construct(SerializerInterface $serializer, StatisticModel $statisticModel)
    {
        $this->serializer = $serializer;
        $this->statisticModel = $statisticModel;
    }

    /**
     * @Route("/statistics/get/gamesPerWeek", name="statistics_get_games_per_week")
     * @return JsonResponse
     */
    public function statisticsGetGamesPerWeek(): JsonResponse
    {
        return $this->statisticModel->getGamesPerWeek();
    }
}
