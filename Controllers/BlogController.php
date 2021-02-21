<?php

namespace Application\Controllers;

use Artister\Web\Mvc\Controller;
use Artister\Web\Mvc\IActionResult;

class BlogController extends Controller
{
    public function index() : IActionResult
    {
        return $this->view();
    }
}