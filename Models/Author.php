<?php

namespace Application\Models;

use Artister\Entity\IEntity;
use Artister\System\Collections\IList;

class Author implements IEntity
{
    private int $Id;
    private int $UserId;
    private string $Name;

    // navigation properties
    private User $User;
    private IList $Postes;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
        $this->$name = $value;
    }
}