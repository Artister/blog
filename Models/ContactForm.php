<?php

namespace Application\Models;

class ContactForm
{
    private string $Name;
    private string $Email;
    private string $Subject;
    private string $Message;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
         $this->$name = strip_tags(htmlspecialchars($value));
    }

    public function isValide() : bool
    {
        if (empty($this->Name) || empty($this->Email) || empty($this->Subject) || empty($this->Message))
        {
            return false;
        }

        if (!filter_var($this->Email, FILTER_VALIDATE_EMAIL))
        {
            return false;
        }

        return true;
    }
}
