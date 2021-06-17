<?php

namespace Application\Models;

use DevNet\Entity\IEntity;
use DevNet\System\Collections\IList;

class Author implements IEntity
{
    private int $Id;
    private int $UserId;
    private string $Name;
    private string $Gender;
    private ?string $Occupation;
    private ?string $Location;
    private ?string $Picture;
    private ?string $Description;
    private ?string $Email;
    private ?string $Phone;
    private ?string $Link;

    // navigation properties
    private User $User;
    private IList $Posts;

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