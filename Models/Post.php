<?php

namespace Application\Models;

use DevNet\Entity\IEntity;
use DevNet\System\Collections\IList;
use DateTime;

class Post implements IEntity
{
    private int $Id;
    private int $SectionId;
    private int $AuthorId;
    private string $Title;
    private string $Slug;
    private string $Excerpt;
    private string $Image;
    private string $Content;
    private DateTime $EditedAt;

    // navigation properties
    private Author $Author;
    private Section $Section;
    private IList $Comments;

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
