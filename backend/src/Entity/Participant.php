<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
 */
class Participant
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
    private $eloChange;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\History", inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $history;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEloChange(): ?int
    {
        return $this->eloChange;
    }

    public function setEloChange(int $eloChange): self
    {
        $this->eloChange = $eloChange;

        return $this;
    }

    public function getHistory(): ?History
    {
        return $this->history;
    }

    public function setHistory(?History $history): self
    {
        $this->history = $history;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }
}
