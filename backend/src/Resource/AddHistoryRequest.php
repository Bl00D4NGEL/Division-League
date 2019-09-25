<?php
namespace App\Resource;


use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

class AddHistoryRequest
{
    /**
     * @Type("int")
     * @var int $winner
     */
    public $winner;

    /**
     * @Type("int")
     * @var int $loser
     */
    public $loser;

    /**
     * @SerializedName("proofUrl")
     * @Type("string")
     * @var string $proofUrl
     */
    public $proofUrl;

    public function isValid(): bool
    {
        return (
            isset($this->winner)
            && isset($this->loser)
            && isset($this->proofUrl)
            && $this->winner !== $this->loser
        );
    }
}