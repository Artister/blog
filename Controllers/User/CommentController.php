<?php

namespace Application\Controllers\User;

use Application\Models\Comment;
use DevNet\Core\Controller\AbstractController;
use DevNet\Core\Controller\IActionResult;
use DevNet\Core\Identity\IdentityManager;
use DevNet\Core\Identity\UserManager;
use DevNet\Entity\EntityContext;
use DevNet\System\Linq;
use DateTime;

class CommentController extends AbstractController
{
    private IdentityManager $Manager;
    private UserManager $Users;
    private EntityContext $DbManager;

    public function __construct(IdentityManager $manager, UserManager $users, EntityContext $dbManager)
    {
        $this->Manager = $manager;
        $this->Users = $users;
        $this->DbManager = $dbManager;
    }
    public function index(): IActionResult
    {
        $user = $this->Users->getUser();
        // get the profile
        $profile = $this->DbManager->Authors->where(fn ($author) => $author->UserId == $user->Id)->first();
        $mycomments = $this->DbManager->Comments->where(fn ($comment) => $comment->AuthorId == $profile->Id);
        $this->ViewData['mycomments'] = $mycomments;
        $this->ViewData['total'] = count($mycomments->toArray());
        return $this->view();
    }
    public function new(int $id): IActionResult
    {
        $user = $this->Users->getUser();
        if (!$user) { // Not signed In
            return $this->redirect("/user/account/login");
        }
        // if signed in
        $profile = $this->DbManager->Authors->where(fn ($author) => $author->UserId == $user->Id)->first();
        $comment = new Comment();
        $comment->PostId = $id;
        $comment->AuthorId = $profile->Id;
        $comment->Content = $_POST["Message"];
        $comment->EditedAt = new DateTime('now');
        $this->DbManager->Comments->add($comment);
        $result = $this->DbManager->save();
        return $this->redirect("/blog/post/{$id}");
    }
}
