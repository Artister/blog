<?php

namespace Application\Models;

use Artister\Entity\IEntity;
use DateTime;

class Comment implements IEntity
{
    private int $Id;
    private int $PostId;
    private int $AuthorId;
    private string $Content;
    private DateTime $EditedAt;

    // navigation properties
    private Post $Post;
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
