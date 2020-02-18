<?php
namespace App\Resource;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

class AddHistoryRequest
{
    /**
     * @Type("array")
     * @var int[] $winner
     */
    public $winner = [];

    /**
     * @Type("array")
     * @var int[] $loser
     */
    public $loser = [];

    /**
     * @SerializedName("proofUrl")
     * @Type("array")
     * @var string[] $proofUrl
     */
    public $proofUrl;

    /**
     * @SerializedName("winnerTeamName"))
     * @Type("string")
     * @var string
     */
    public $winnerTeamName;

    /**
     * @SerializedName("loserTeamName"))
     * @Type("string")
     * @var string
     */
    public $loserTeamName;

    public function isValid(): bool
    {
        foreach ($this->winner as $player) {
            if (in_array($player, $this->loser, true)) {
                return false;
            }
        }
        return (
            count($this->winner) > 0
            && count($this->loser) > 0
            && count($this->proofUrl) > 0
        );
    }
}
