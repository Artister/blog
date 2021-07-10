<?php

namespace Application\Models;

use DevNet\Entity\IEntity;
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
        if (!property_exists($this, $name)) {
            throw new \Exception("The property {$name} doesn't exist.");
        }

        $this->$name = $value;
    }
}
