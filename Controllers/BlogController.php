<?php

namespace Application\Controllers;

use DevNet\Entity\EntityContext;
use DevNet\Core\Controller\AbstractController;
use DevNet\Core\Controller\IActionResult;

class BlogController extends AbstractController
{
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
