<?php
namespace App\Resource;

use App\Entity\History;
use JMS\Serializer\Annotation\Type;

class GetHistoryRequest
{
    /**
     * @var History $history
     */
    public $history;

    /**
     * @Type("int")
     * @var int $limit
     */
    public $limit = 0;
}