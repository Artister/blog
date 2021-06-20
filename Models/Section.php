<?php

namespace Application\Models;

use DevNet\Entity\IEntity;
use DevNet\System\Collections\IList;

class Section implements IEntity
{
    private int $Id;
    private string $Title;
    private string $Slug;
    private ?string $Image;
    private string $Description;

    // navigation properties
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
