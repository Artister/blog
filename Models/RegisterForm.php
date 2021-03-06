<?php

namespace Application\Models;

class RegisterForm
{
    private string $Name;
    private string $Email;
    private string $Password;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
         $this->$name = $value;
    }

    public function isValide() : bool
    {
        if (empty($this->Name) || empty($this->Email) || empty($this->Password))
        {
            return false;
        }

        return true;
    }
}
