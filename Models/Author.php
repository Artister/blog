<?php

namespace Application\Models;

use DevNet\Entity\IEntity;
use DevNet\System\Collections\IList;

class Author implements IEntity
{
    private int $Id;
    private int $UserId;
    private string $Name;

    // navigation properties
    private User $User;
    private IList $Posts;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
        $this->$name = $value;
    }
}