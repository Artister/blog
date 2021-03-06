<?php

namespace Application\Models;

use Artister\Web\Identity\User as IdentityUser;

class User extends IdentityUser
{
    // navigation properties
    private Author $Author;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
        $this->$name = $value;
    }
}
