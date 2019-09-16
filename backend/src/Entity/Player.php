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

    public function getQpoints()
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
            "playerId" => $this->getPlayerId()
        ];
    }
}
