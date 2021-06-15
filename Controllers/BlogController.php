<?php

namespace Application\Controllers;

use DevNet\Entity\EntityContext;
use DevNet\Web\Mvc\Controller;
use DevNet\Web\Mvc\IActionResult;
use DevNet\Entity\EntitySet;
use DevNet\System\Linq;
use DevNet\Web\Mvc\Filters\HttpMethodFilter;

class BlogController extends Controller
{
    public function __construct(EntityContext $dbManager)
    {
        $this->DbManager = $dbManager;
    }

    public function index(): IActionResult
    {
        $sections = $this->DbManager->Sections;
        $this->ViewData['sections'] = $sections->toArray();

        $posts = $this->DbManager->Posts;
        $this->ViewData['posts'] = $posts->orderByDescending(fn ($p) => $p->Id)->take(5);

        $comments = $this->DbManager->Comments;

        $this->ViewData['comments'] = $comments->orderByDescending(fn ($c) => $c->Id)->take(6);

        $this->ViewData['page'] = 1;
        return $this->view();
    }

    public function page($id): IActionResult
    {
        # code...
        //  resultat = skip((pageNumber - 1) * limit)->take(limit)

        $posts = $this->DbManager->Posts;
        if ($id < 1) {
            return $this->redirect("home/error");
        }
        $limit = 5;
        $offset = ($id - 1) * $limit;
        //$result = $posts->skip($offset)->take(2)->toArray();

        $sections = $this->DbManager->Sections;
        $this->ViewData['sections'] = $sections->toArray();
        $this->ViewData['posts'] = $posts->skip($offset)->take($limit)->toArray();
        $this->ViewData['page'] = $id;

        $comments = $this->DbManager->Comments;

        $this->ViewData['comments'] = $comments->orderByDescending(fn ($c) => $c->Id)->take(6);
        return $this->View();
    }
    public function post(int $id): IActionResult
    {
        $sections = $this->DbManager->Sections;
        $this->ViewData['sections'] = $sections->toArray();

        $comments = $this->DbManager->Comments->orderByDescending(fn ($c) => $c->Id);
        $offset = count($comments->toArray());
        //$this->ViewData['comments'] = $comments->orderByDescending(fn ($c) => $c->Id)->take(6);
        // ($offset > 6 ? $this->ViewData['comments'] = $comments->skip($offset - 6)->take(6)->toArray()
        //     : $this->ViewData['comments'] = $comments->toArray());
        ($offset > 6 ? $this->ViewData['comments'] = $comments->take(6)->toArray()
            : $this->ViewData['comments'] = $comments->toArray());
        $post = $this->DbManager->Posts->find($id);

        if (!$post) {
            return $this->redirect("home/error");
        }
        return $this->view($post);
    }
}
