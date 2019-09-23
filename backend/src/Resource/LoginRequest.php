<?php


namespace App\Resource;


use JMS\Serializer\Annotation\Type;

class LoginRequest
{
    /**
     * @Type("string")
     * @var string $user
     */
    public $user;

    /**
     * @Type("string")
     * @var string $password
     */
    public $password;

    public function isValid(): bool
    {
        return (
            isset($this->password)
            && isset($this->user)
        );
    }
}