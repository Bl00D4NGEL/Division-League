<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    public const MAX_ELO_DIFFERENCE = 300;

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

    /**
     * @param Player[] $players
     * @return Team
     */
    public function addPlayers(array $players): self {
        foreach ($players as $player) {
            $this->addPlayer($player);
        }

        return $this;
    }

    public function addPlayer(Player $player): self {
        if (!$this->doesPlayerExist($player)) {
            $this->players[] = $player;
        }
        return $this;
    }

    private function doesPlayerExist(Player $player): bool {
        foreach ($this->getPlayers() as $p) {
            if ($p->getId() === $player->getId()) {
                return true;
            }
        }
        return false;
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

    public function getAverageElo(): int
    {
        return ceil(
            array_sum(
                array_map(function (Player $val) {
                    return $val->getElo();
                }, $this->getPlayers())
            ) / count($this->getPlayers())
        );
    }

    public function isPlayerEloDifferenceValid(): bool {
        $elos = array_map(function (Player $val) {
            return $val->getElo();
        }, $this->getPlayers());
        $max = max($elos);
        $min = min($elos);
        $diff = $max - $min;
        return $diff < self::MAX_ELO_DIFFERENCE;
    }
}
