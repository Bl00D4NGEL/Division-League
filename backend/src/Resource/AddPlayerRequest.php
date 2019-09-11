<?php
namespace App\Resource;

use App\Entity\Player;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

class AddPlayerRequest
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
     * @var int $eloRating
     */
    public $eloRating = 1000;

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
        );
    }
}