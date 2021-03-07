<?php

namespace Application\Controllers;

use Artister\Web\Mvc\Controller;
use Artister\Web\Mvc\IActionResult;
use Artister\Web\Mvc\Filters\AuthorizeFilter;
use Artister\Web\Identity\IdentityManager;
use Artister\Web\Identity\UserManager;
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
    }

    public function index() : IActionResult
    {
        $user = $this->Users->getUser();
        $this->ViewData['Email'] = $user->Username;
        return $this->view('account/index');
    }

    public function login(LoginForm $form) : IActionResult
    {
        $user = $this->HttpContext->User;

        if ($user->isAuthenticated())
        {
            return $this->redirect('account/index');
        }

        if (!$form->isValide())
        {
            return $this->view();
        }

        $this->Identity->signIn($form->Username, $form->Password, $form->Remember);

        return $this->redirect('account/index');
    }

    public function logout() : IActionResult
    {
        $this->Identity->signOut();
        return $this->redirect('account/login');
    }

    public function register(RegisterForm $form) : IActionResult
    {
        if (!$form->isValide())
        {
            return $this->view('account/register');
        }

        $user = new User();
        $user->Username = $form->Email;
        $user->Password = $form->Password;
        $this->Users->create($user);

        return $this->content("User {$user->Username} has been created");
    }
}
