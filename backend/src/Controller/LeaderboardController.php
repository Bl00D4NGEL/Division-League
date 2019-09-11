<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LeaderboardController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function homepage()
    {
        return new Response("Homepage");
    }
}
