<?php


namespace App\ValueObjects;


use App\Entity\History;
use App\Entity\Team;

class RichHistory extends History
{
    /** @var Team */
    private $loserObject;

    /** @var Team */
    private $winnerObject;

    /** @var History */
    private $history;

    public function getHistory(): ?History {
        return $this->history;
    }

    public function setHistory(History $history): self {
        $this->history = $history;

        return $this;
    }

    public function getLoserObject(): ?Team
    {
        return $this->loserObject;
    }

    public function setLoserObject(Team $loserObject): self
    {
        $this->loserObject = $loserObject;

        return $this;
    }

    public function getWinnerObject(): ?Team {
        return $this->winnerObject;
    }


    public function setWinnerObject(Team $winnerObject): self
    {
        $this->winnerObject = $winnerObject;

        return $this;
    }
}
