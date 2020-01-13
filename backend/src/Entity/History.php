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
     * @ORM\Column(type="integer")
     */
    private $winnerGain;

    /**
     * @ORM\Column(type="integer")
     */
    private $loserGain;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $proofUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function asArray(): array {
        $data = [];
        foreach($this as $field => $value) {
            $data[$field] = $value;
        }
        return $data;
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

    public function getWinnerGain(): ?int
    {
        return $this->winnerGain;
    }

    public function setWinnerGain(int $winnerGain): self
    {
        $this->winnerGain = $winnerGain;

        return $this;
    }

    public function getLoserGain(): ?int
    {
        return $this->loserGain;
    }

    public function setLoserGain(int $loserGain): self
    {
        $this->loserGain = $loserGain;

        return $this;
    }

    public function getProofUrl(): ?string
    {
        return $this->proofUrl;
    }

    public function setProofUrl(string $proof_url): self
    {
        $this->proofUrl = $proof_url;

        return $this;
    }
}
