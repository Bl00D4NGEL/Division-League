<?php
namespace App\Resource;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

final class AddHistoryMultiRequest
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
     * @Type("string")
     * @var string $proofUrl
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
        $playersAreDifferent = true;
        foreach ($this->winner as $player) {
            if (in_array($player, $this->loser, true)) {
                $playersAreDifferent = false;
            }
        }
        return (
            count($this->winner) > 0
            && count($this->loser) > 0
            && isset($this->proofUrl)
            && $playersAreDifferent
        );
    }
}
