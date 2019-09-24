<?php

namespace App\Entity;

use App\Resource\AddPlayerRequest;
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
    private $elo;

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

    public function getElo(): ?int
    {
        return $this->elo;
    }

    public function setElo(int $elo): self
    {
        $this->elo = $elo;

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
        $this->setElo($this->getElo() + $this->calculateEloChangeForWinAgainst($enemy));
        return $this;
    }

    public function loseAgainst(Player $enemy): Player
    {
        $this->setLoses($this->getLoses() + 1);
        $this->setElo($this->getElo() + $this->calculateEloChangeForLoseAgainst($enemy));
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
        $kFactor = ($this->getElo() + $enemy->getElo()) / 100;
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
        return 10 ** ($this->elo / 400);
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        $data = [];
        foreach ($this as $field => $value) {
            $data[$field] = $value;
        }
        return $data;
    }

    public static function fromAddPlayerRequest(AddPlayerRequest $request): self
    {
        return (new self())
            ->setDivision($request->division)
            ->setElo($request->elo)
            ->setName($request->name)
            ->setPlayerId($request->playerId)
            ->setWins($request->wins)
            ->setLoses($request->loses);
    }
}
