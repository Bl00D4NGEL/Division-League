<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Player;
use App\Entity\History;

use Doctrine\ORM\EntityManagerInterface;

class LeaderboardController extends AbstractController {
    /**
     * @Route("/", name="leaderboard_homepage")
     */
    public function homepage(EntityManagerInterface $em) {
        $results = $em->getRepository(Player::class)->findAll();
        
        $players = [];
        foreach ($results as $player) {
            array_push($players, array(
                "wins" => $player->getWins(),
                "loses" => $player->getLoses(),
                "elo" => $player->getEloRating(),
                "name" => $player->getName(),
                "id" => $player->getId(),
                "playerId" => $player->getPlayerId()
            ));
        }
        return new JsonResponse($players);
    }


    /**
     * @Route("/leaderboard/add/player", name="add_leaderboard_player")
     */
    public function playerAdd(EntityManagerInterface $em) {
        $player = new Player();
        $player->setDivision("XXII")
        ->setEloRating(1000)
        ->setName("shooti")
        ->setPlayerId(29489)
        ->setWins(0)
        ->setLoses(0);

        $em->persist($player);
        $em->flush();
        return new Response(sprintf('new player id is %d', $player->getId()));
    }
}
