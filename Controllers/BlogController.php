<?php

namespace Application\Controllers;

use Artister\Entity\EntityContext;
use Artister\Web\Mvc\Controller;
use Artister\Web\Mvc\IActionResult;

class BlogController extends Controller
{
    private EntityContext $DbManager;

    public function __construct(EntityContext $dbManager)
    {
        $this->DbManager = $dbManager;
    }

    public function index() : IActionResult
    {
        return $this->view();
    }

    public function post() : IActionResult
    {
        return $this->view();
    }
}