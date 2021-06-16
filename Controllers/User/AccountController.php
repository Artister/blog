<?php

namespace Application\Controllers\User;

use Application\Models\Author;
use DevNet\Web\Mvc\Controller;
use DevNet\Web\Mvc\IActionResult;
use DevNet\Web\Mvc\Filters\AuthorizeFilter;
use DevNet\Web\Mvc\Filters\AntiForgeryFilter;
use DevNet\Web\Identity\IdentityManager;
use DevNet\Web\Identity\UserManager;
use Application\Models\LoginForm;
use Application\Models\RegisterForm;
use Application\Models\User;
use DevNet\System\Linq;
use DevNet\Entity\EntityContext;

class AccountController extends Controller
{
    private IdentityManager $Identity;
    private UserManager $Users;
    private EntityContext $DbManager;

    public function __construct(IdentityManager $identity, UserManager $users, EntityContext $dbManager)
    {
        $this->Identity = $identity;
        $this->Users = $users;
        $this->DbManager = $dbManager;

        $this->filter('index', AuthorizeFilter::class);
        $this->filter('login', AntiForgeryFilter::class);
        $this->filter('register', AntiForgeryFilter::class);
    }

    public function index(): IActionResult
    {
        $user = $this->Users->getUser();
        $this->ViewData['Email'] = $user->Username;
        return $this->view();
    }

    public function login(LoginForm $form): IActionResult
    {
        $user = $this->HttpContext->User;

        if ($user->isAuthenticated()) {
            $this->ViewData['author'] = $this->DbManager->Authors->find($user->Id);
            return $this->redirect('/user/account');
        }

        if (!$form->isValide()) {
            return $this->view();
        }

        $this->Identity->signIn($form->Username, $form->Password, $form->Remember);

        return $this->redirect('/user/account');
    }

    public function logout(): IActionResult
    {
        $this->Identity->signOut();
        return $this->redirect('/user/account/login');
    }

    public function register(RegisterForm $form): IActionResult
    {
        if (!$form->isValide()) {
            return $this->view();
        }

        $user = new User();
        $user->Username = $form->Email;
        $user->Password = $form->Password;
        $this->Users->create($user);

        // find the last user
        $last = $this->DbManager->Users->last();
        // profile creation
        $author = new Author();
        $author->UserId = $last->Id;
        $author->Name = $form->Name;
        $author->Gender = "My Gender";
        $author->Occupation = "My Occupation";
        $author->Location = "My Loction";
        $author->Description = "My Description";
        $author->Phone = "9999999999";
        $author->Email =  $form->Email;
        $author->Link = "My link";
        $author->Photo = "avatar1.png";
        $this->DbManager->Authors->add($author);
        $this->DbManager->save();

        return $this->content("User {$user->Username} and its profile has been created.");
    }
}
