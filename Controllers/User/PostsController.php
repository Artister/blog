<?php

namespace Application\Controllers\User;

use DevNet\Core\Controller\AbstractController;
use DevNet\Core\Controller\IActionResult;
use DevNet\Core\Controller\Filters\AuthorizeFilter;

class PostsController extends AbstractController
{
    public function __construct()
    {
        $this->filter(self::class, AuthorizeFilter::class);
    }

    public function index() : IActionResult
    {
        return $this->view();
    }
}
