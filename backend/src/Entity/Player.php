<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $league;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participant", mappedBy="player", orphanRemoval=true)
     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

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

    public function getLeague(): ?string
    {
        return $this->league;
    }

    public function setLeague(string $league): self
    {
        $this->league = $league;

        return $this;
    }

    public function win(int $eloGain): self
    {
        $this->elo += $eloGain;
        $this->wins++;
        return $this;
    }

    public function lose(int $eloLose): self
    {
        $this->elo += $eloLose;
        $this->loses++;
        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function isDeleted(): bool {
        return $this->getDeleted() === true;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setPlayer($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            // set the owning side to null (unless already changed)
            if ($participant->getPlayer() === $this) {
                $participant->setPlayer(null);
            }
        }

        return $this;
    }
}
