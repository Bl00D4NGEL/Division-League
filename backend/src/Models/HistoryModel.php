<?php

namespace App\Models;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;

use App\Entity\Player;
use App\Entity\History;

use App\Helper\EloChangeCalculator;

class HistoryModel  {
    private $em;
    private $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer) {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    public function addHistory(int $winnerId, int $loserId, string $proofUrl): array {
    if ($winnerId !== '' && $loserId !== '') {
        $historyObject = $this->insertHistory($winnerId, $loserId, $proofUrl);
        $changes = $this->updateEloForPlayers($winnerId, $loserId);
        
        $this->em->flush();
        return array(
            "success" => true,
            "historyId" => $historyObject->getId(),
            "changes" => $changes,
        );
    }
    else {
        return array(
            "error" => true,
            "message" => 'Sent data is not sufficient'
        );
    }
}

private function insertHistory(int $winnerId, int $loserId, string $proofUrl): History {
    $historyEntry = new History();
    $historyEntry->setWinnerId($winnerId);
    $historyEntry->setLoserId($loserId);
    $historyEntry->setProofUrl($proofUrl);
    $this->em->persist($historyEntry);
    return $historyEntry;
}

private function updateEloForPlayers(int $winnerId, int $loserId): array {
    $playerRepository = $this->em->getRepository(Player::class);

        $eloCalc = new EloChangeCalculator();

        $eloCalc->setWinner($playerRepository->findOneBy([
            'id' => $winnerId
        ]));
        
        $eloCalc->setLoser($playerRepository->findOneBy([
            'id' => $loserId
        ]));

        return $eloCalc->updatePlayers();
}

public function getHistory(): array {
    $playerRepository = $this->em->getRepository(Player::class);
        $players = $playerRepository->findAll();
        $playerMap = [];

        foreach ($players as $player) {
            $playerMap[$player->getId()] = $this->serializer->serialize($player, 'json');
        }

        $playerRepository = $this->em->getRepository(History::class);
        $histories = $playerRepository->findAll();
        $responseHistories = [];
        foreach ($histories as $historyObject) {
            $responseHistories[] = [
                "winner" => json_decode($playerMap[$historyObject->getWinnerId()]),
                "loser" => json_decode($playerMap[$historyObject->getLoserId()]),
                "proofUrl" => $historyObject->getProofUrl(),
                "id" => $historyObject->getId()
            ];
        }
        return $responseHistories;
}
}