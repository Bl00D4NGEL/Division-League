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
    private $winnerId;

    /**
     * @ORM\Column(type="integer")
     */
    private $loserId;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLoserId(): ?int
    {
        return $this->loserId;
    }

    public function setLoserId(int $loserId): self
    {
        $this->loserId = $loserId;
        
        return $this;
    }
}
