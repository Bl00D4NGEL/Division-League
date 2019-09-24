<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $playerId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", options={"default": 1000})
     */
    private $eloRating;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $division;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $wins;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $loses;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerId(): ?int
    {
        return $this->playerId;
    }

    public function setPlayerId(int $playerId): self
    {
        $this->playerId = $playerId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEloRating(): ?int
    {
        return $this->eloRating;
    }

    public function setEloRating(int $eloRating): self
    {
        $this->eloRating = $eloRating;

        return $this;
    }

    public function getDivision(): ?string
    {
        return $this->division;
    }

    public function setDivision(string $division): self
    {
        $this->division = strtoupper($division);

        return $this;
    }

    public function getWins(): ?int
    {
        return $this->wins;
    }

    public function setWins(int $wins): self
    {
        $this->wins = $wins;

        return $this;
    }

    public function getLoses(): ?int
    {
        return $this->loses;
    }

    public function setLoses(int $loses): self
    {
        $this->loses = $loses;

        return $this;
    }

    public function winAgainst(Player $enemy): Player
    {
        $this->setWins($this->getWins() + 1);
        $this->setEloRating($this->getEloRating() + $this->calculateEloChangeForWinAgainst($enemy));
        return $this;
    }

    public function loseAgainst(Player $enemy): Player
    {
        $this->setLoses($this->getLoses() + 1);
        $this->setEloRating($this->getEloRating() + $this->calculateEloChangeForLoseAgainst($enemy));
        return $this;
    }

    public function calculateEloChangeForLoseAgainst(Player $enemy)
    {
        return ceil($this->calculateKFactorAgainst($enemy) * (0 - $this->calculateWinChanceAgainst($enemy)));
    }

    public function calculateEloChangeForWinAgainst(Player $enemy)
    {
        return ceil($this->calculateKFactorAgainst($enemy) * (1 - $this->calculateWinChanceAgainst($enemy)));
    }

    private function calculateKFactorAgainst(Player $enemy)
    {
        $kFactor = ($this->getEloRating() + $enemy->getEloRating()) / 100;
        if ($kFactor < 16) {
            $kFactor = 16;
        }
        return $kFactor;
    }

    private function calculateWinChanceAgainst(Player $enemy)
    {
        return $this->getQpoints() / ($this->getQpoints() + $enemy->getQpoints());
    }

    private function getQpoints()
    {
        return 10 ** ($this->eloRating / 400);
    }


    public function asArray()
    {
        return [
            "wins" => $this->getWins(),
            "loses" => $this->getLoses(),
            "elo" => $this->getEloRating(),
            "name" => $this->getName(),
            "id" => $this->getId(),
            "playerId" => $this->getPlayerId(),
            "division" => $this->getDivision(),
        ];
    }
}
