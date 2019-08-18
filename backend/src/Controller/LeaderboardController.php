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
     * @Route("/leaderboard/add/history", name="add_leaderboard_history")
     */
    public function addHistory(EntityManagerInterface $em, Request $req) {
        $body = $req->getContent();
        $data = json_decode($body, true);
        $winnerId = $data['winnerId'];
        $loserId = $data['loserId'];
        if ($winnerId !== '' && $loserId !== '') {
            $his = new History();
            $his->setPlayerOneId($winnerId);
            $his->setPlayerTwoId($loserId);
            $his->setWinnerId($winnerId);
            $em->persist($his);

            $rep = $em->getRepository(Player::class);
            $winner = $rep->findOneBy([
                'id' => $winnerId
            ]);

            $winner->setWins($winner->getWins() + 1);

            $loser = $rep->findOneBy([
                'id' => $loserId
            ]);
            $loser->setLoses($loser->getLoses() + 1);

            $em->flush();
            return new JsonResponse(array(
                "success" => true,
                "historyId" => $his->getId()
            ));
        }
        else {
            return new JsonResponse(array(
                "error" => true,
                "message" => 'Sent data is not sufficient'
            ));
        }

        var_dump($data);
        return new Response($data);
    }

    /**
     * @Route("/leaderboard/add/player", name="add_leaderboard_player")
     */
    public function add(EntityManagerInterface $em) {
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
