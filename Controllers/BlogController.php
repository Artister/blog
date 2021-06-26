<?php

namespace Application\Controllers;

use DevNet\Core\Controller\AbstractController;
use DevNet\Core\Controller\IActionResult;
use DevNet\Core\Controller\Filters\HttpMethodFilter;
use DevNet\Entity\EntityContext;
use DevNet\Entity\EntitySet;
use DevNet\System\Linq;

class BlogController extends AbstractController
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

        $this->ViewData['formatter'] = new \Application\Lib\Formatter();

        return $this->view();
    }

    public function section(int $id): IActionResult
    {
        $sections = $this->DbManager->Sections;
        $this->ViewData['sections'] = $sections->toArray();

        $comments = $this->DbManager->Comments;
        $offset = count($comments->toArray());
        ($offset > 6 ? $this->ViewData['comments'] = $comments->skip($offset - 6)->take(6)->toArray()
            : $this->ViewData['comments'] = $comments->toArray());

        $this->ViewData['page'] = 1;

        $section = $sections->find($id);
        if (!$section) {
            return $this->redirect("home/error");
        }

        $posts = $this->DbManager->Posts->where(fn ($post) => $post->SectionId == $id)
            ->take(5)->toArray();
        $this->ViewData['posts'] = $posts;
        // give the view the section
        $this->ViewData['section'] = $section;

        return $this->view();
    }

    public function page($id): IActionResult
    {
        $posts = $this->DbManager->Posts;
        if ($id < 1) {
            return $this->redirect("home/error");
        }
        $limit = 5;
        $offset = ($id - 1) * $limit;
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

        ($offset > 6 ? $this->ViewData['comments'] = $comments->take(6)->toArray()
            : $this->ViewData['comments'] = $comments->toArray());
        $post = $this->DbManager->Posts->find($id);

        if (!$post) {
            return $this->redirect("home/error");
        }

        $this->ViewData['formatter'] = new \Application\Lib\Formatter();

        return $this->view("Blog/Post", $post);
    }
}
