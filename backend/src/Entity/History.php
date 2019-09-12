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
    private $winner;

    /**
     * @ORM\Column(type="integer")
     */
    private $loser;

    /**
     * @ORM\Column(type="string")
     */
    private $proofUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWinner(): ?int
    {
        return $this->winner;
    }

    public function setWinner(int $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getLoser(): ?int
    {
        return $this->loser;
    }

    public function setLoser(int $loser): self
    {
        $this->loser = $loser;
        
        return $this;
    }

    public function getProofUrl(): ?string {
        return $this->proofUrl;
    }

    public function setProofUrl(string $proofUrl): self {
        $this->proofUrl = $proofUrl;

        return $this;
    }

    /**
     * @return array
     */
    public function asArray(): array {
        $data = [];
        foreach($this as $field => $value) {
            $data[$field] = $value;
        }
        return $data;
    }
}
