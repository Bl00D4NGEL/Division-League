<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var Player[]
     */
    private $players = [];


    public function getId(): ?int
    {
        return $this->id;
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

    public function isSoloTeam(): bool {
        return count($this->players) === 1;
    }

    /**
     * @return Player[]
     */
    public function getPlayers(): array {
        return $this->players;
    }

    public function addPlayer(Player $player): self {
        $this->players[] = $player;
        return $this;
    }

    /**
     * @return array
     */
    public function asArray(): array {
        $data = [];
        foreach($this as $field => $value) {
            if (is_array($value) && $value[0] instanceof Player) {
                $data[$field] = array_map(function (Player $player) {
                    return $player->asArray();
                }, $value);
            } else {
                $data[$field] = $value;
            }
        }
        return $data;
    }

    public function win(int $eloGain): self
    {
        foreach ($this->getPlayers() as $player) {
            $player->win($eloGain);
        }
        return $this;
    }

    public function lose(int $eloLose): self
    {
        foreach ($this->getPlayers() as $player) {
            $player->lose($eloLose);
        }
        return $this;
    }
}
