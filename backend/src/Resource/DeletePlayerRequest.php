<?php
namespace App\Resource;

use JMS\Serializer\Annotation\Type;

class DeletePlayerRequest
{
    /**
     * @Type("int")
     * @var int $id
     */
    public $id;

    public function isValid(): bool
    {
        return isset($this->id);
    }
}
