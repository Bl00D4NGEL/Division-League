<?php

namespace App\Resource;

use App\Entity\User;
use JMS\Serializer\Annotation\Type;

class RegisterRequest
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

    /**
     * @Type("string")
     * @var string $role
     */
    public $role;

    public function isValid(): bool
    {
        return (
            isset($this->password)
            && isset($this->user)
            && in_array($this->role, User::ROLES, true)
        );
    }
}