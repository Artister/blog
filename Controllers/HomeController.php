<?php

namespace Application\Controllers;

use DevNet\Web\Mvc\Controller;
use DevNet\Web\Mvc\IActionResult;

class HomeController extends Controller
{
    public function index() : IActionResult
    {
        return $this->view();
    }

    public function about() : IActionResult
    {
        return $this->view();
    }

    public function contact() : IActionResult
    {
        return $this->view();
    }

    public function error() : IActionResult
    {
        return $this->view();
    }
}