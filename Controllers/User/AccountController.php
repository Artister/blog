<?php

namespace Application\Controllers\User;

use DevNet\Web\Mvc\Controller;
use DevNet\Web\Mvc\IActionResult;
use DevNet\Web\Mvc\Filters\AuthorizeFilter;
use DevNet\Web\Mvc\Filters\AntiForgeryFilter;
use DevNet\Web\Identity\IdentityManager;
use DevNet\Web\Identity\UserManager;
use Application\Models\LoginForm;
use Application\Models\RegisterForm;
use Application\Models\User;

class AccountController extends Controller
{
    private IdentityManager $Identity;
    private UserManager $Users;

    public function __construct(IdentityManager $identity, UserManager $users)
    {
        $this->Identity = $identity;
        $this->Users = $users;

        $this->filter('index', AuthorizeFilter::class);
        $this->filter('login', AntiForgeryFilter::class);
        $this->filter('register', AntiForgeryFilter::class);
    }

    public function index() : IActionResult
    {
        $user = $this->Users->getUser();
        $this->ViewData['Email'] = $user->Username;
        return $this->view();
    }

    public function login(LoginForm $form) : IActionResult
    {
        $user = $this->HttpContext->User;

        if ($user->isAuthenticated())
        {
            return $this->redirect('/user/account');
        }

        if (!$form->isValide())
        {
            return $this->view();
        }

        $this->Identity->signIn($form->Username, $form->Password, $form->Remember);

        return $this->redirect('/user/account');
    }

    public function logout() : IActionResult
    {
        $this->Identity->signOut();
        return $this->redirect('/user/account/login');
    }

    public function register(RegisterForm $form) : IActionResult
    {
        if (!$form->isValide())
        {
            return $this->view();
        }

        $user = new User();
        $user->Username = $form->Email;
        $user->Password = $form->Password;
        $this->Users->create($user);

        return $this->content("User {$user->Username} has been created");
    }
}
