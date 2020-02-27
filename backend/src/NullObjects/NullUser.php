<?php

namespace App\NullObjects;


use App\Entity\User;

class NullUser extends User
{
    public function asArray(): array
    {
        return [];
    }
}
