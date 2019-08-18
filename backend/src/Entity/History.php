<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoryRepository")
 */
class History
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
    private $playerOneId;

    /**
     * @ORM\Column(type="integer")
     */
    private $playerTwoId;

    /**
     * @ORM\Column(type="integer")
     */
    private $winnerId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerOneId(): ?int
    {
        return $this->playerOneId;
    }

    public function setPlayerOneId(int $playerOneId): self
    {
        $this->playerOneId = $playerOneId;

        return $this;
    }

    public function getPlayerTwoId(): ?int
    {
        return $this->playerTwoId;
    }

    public function setPlayerTwoId(int $playerTwoId): self
    {
        $this->playerTwoId = $playerTwoId;

        return $this;
    }

    public function getWinnerId(): ?int
    {
        return $this->winnerId;
    }

    public function setWinnerId(int $winnerId): self
    {
        $this->winnerId = $winnerId;

        return $this;
    }
}
