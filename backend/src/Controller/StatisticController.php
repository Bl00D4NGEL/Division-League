<?php

namespace App\Controller;

use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StatisticController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/statistics/get/gamesPerWeek", name="statistics_get_games_per_week")
     * @param Request $request
     * @return JsonResponse
     */
    public function statisticsGetGamesPerWeek(Request $request)
    {
        return new JsonResponse([
            'hello' => 'world'
        ]);
    }
}
