<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RosterRepository")
 */
class Roster
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
    private $team;

    /**
     * @ORM\Column(type="integer")
     */
    private $player;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?int
    {
        return $this->team;
    }

    public function setTeam(?int $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getPlayer(): ?int
    {
        return $this->player;
    }

    public function setPlayer(?int $player): self
    {
        $this->player = $player;

        return $this;
    }
}
