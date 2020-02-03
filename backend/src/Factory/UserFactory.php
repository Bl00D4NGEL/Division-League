<?php


namespace App\Factory;


use App\Entity\User;
use App\Resource\RegisterRequest;

class UserFactory
{
    public function createFromRequest(RegisterRequest $registerRequest) {
        $user = new User();
        $user->setLoginName($registerRequest->user)
            ->setRole($registerRequest->role)
            ->setPassword($registerRequest->password);
        return $user;
    }
}
