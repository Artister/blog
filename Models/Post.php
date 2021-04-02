<?php

namespace Application\Models;

use Artister\Entity\IEntity;
use Artister\System\Collections\IList;
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
        $this->$name = $value;
    }
}
