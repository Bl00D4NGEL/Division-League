<?php
namespace App\Resource;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

final class AddPlayerRequest
{
    /**
     * @Type("string")
     * @var string $name
     */
    public $name;

    /**
     * @Type("string")
     * @var string $division
     */
    public $division;

    /**
     * @SerializedName("playerId")
     * @Type("int")
     * @var int $playerId
     */
    public $playerId;

    /**
     * @type("string"))
     * @var string $league
     */
    public $league;

    /**
     * @var int $elo
     */
    public $elo = 1000;

    /**
     * @var int $wins
     */
    public $wins = 0;

    /**
     * @var int $loses
     */
    public $loses = 0;

    public function isValid(): bool
    {
        return (
            isset($this->division)
            && isset($this->playerId)
            && isset($this->name)
            && isset($this->league)
        );
    }
}