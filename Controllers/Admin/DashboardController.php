<?php

namespace Application\Controllers\Admin;

use DevNet\Web\Mvc\Controller;
use DevNet\Web\Mvc\IActionResult;
use DevNet\Web\Mvc\Filters\AuthorizeFilter;

class DashboardController extends Controller
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
