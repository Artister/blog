<?php

namespace Application\Models;

use DevNet\Core\Identity\User as IdentityUser;

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
        if (!property_exists($this, $name))
        {
            throw new \Exception("The property {$name} doesn't exist.");
        }
        
        $this->$name = $value;
    }
}
