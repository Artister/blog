<?php

namespace Application\Models;

use Artister\Entity\IEntity;
use Artister\System\Collections\IList;

class Section implements IEntity
{
    private int $Id;
    private string $Title;
    private string $Slug;
    private string $Image;
    private string $Description;

    // navigation properties
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
