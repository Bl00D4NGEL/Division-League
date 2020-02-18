<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="datetime")
     */
    private $createTime;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Proof", mappedBy="history", orphanRemoval=true, cascade={"persist"})
     */
    private $proof;

    public function __construct()
    {
        $this->proof = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function asArray(): array {
        $data = [];
        foreach($this as $field => $value) {
            if ($value instanceof Collection) {
                $data[$field] = $value->getValues();
            } else {
                $data[$field] = $value;
            }
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

    public function getCreateTime(): ?DateTimeInterface
    {
        return $this->createTime;
    }

    public function setCreateTime(?DateTimeInterface $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * @return Collection|Proof[]
     */
    public function getProof(): Collection
    {
        return $this->proof;
    }

    public function addProof(Proof $proof): self
    {
        if (!$this->proof->contains($proof)) {
            $this->proof[] = $proof;
            $proof->setHistory($this);
        }

        return $this;
    }

    public function removeProof(Proof $proof): self
    {
        if ($this->proof->contains($proof)) {
            $this->proof->removeElement($proof);
            // set the owning side to null (unless already changed)
            if ($proof->getHistory() === $this) {
                $proof->setHistory(null);
            }
        }

        return $this;
    }
}
