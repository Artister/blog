<?php

namespace Application\Controllers;

use DevNet\Entity\EntityContext;
use DevNet\Web\Identity\UserManager;
use DevNet\Web\Mvc\Controller;
use DevNet\Web\Mvc\IActionResult;

class SeederController extends Controller
{
    private EntityContext $DbManager;
    private UserManager $Users;

    public function __construct(EntityContext $dbManager, UserManager $users)
    {
        $this->DbManager = $dbManager;
        $this->Users = $users;
    }

    public function index(): IActionResult
    {
        return $this->content("Hi i am a seeder...");
    }
    // We create the admin user and its profile
    public function create_admin(): IActionResult
    {
        // this code is used to create a fake data
        $faker = Faker\Factory::create();
        // Let's create a super user 
        $user = new User();
        $user->Username = "Admin@example.com";
        $user->Password = "Admin001";
        $user->UserRole = new ArrayList("Admin");
        $this->Users->create($user);
        $this->DbManager->save();
        // fetch the admin user
        $user = $this->DbManager->Users->last();
        $userid = $user->Id;
        $email = $user->Username;
        // admin profile creation    
        $author = new Author();
        $author->UserId = $userid;
        $author->Name = $faker->name();
        $author->Email = $email;
        $author->Location = $faker->address();
        $author->Description = $faker->text(200);
        $author->Occupation = $faker->text(50);
        $author->Gender = $faker->text(20);
        $author->Phone = $faker->phoneNumber();
        $author->Photo = "/images/author/admin.png";
        $this->DbManager->Authors->add($author);

        $this->DbManager->save();
        return $this->content(" Admin user and profile created");
    }
    // We create the simple users and their profiles
    public function fake_users(): IActionResult
    {
        // we create 20 users
        for ($i = 1; $i < 21; $i++) {
            $user = new User();
            $user->Username = "User" . $i . "@example.com";
            $user->Password = "User000" . $i;
            $user->UserRole = new ArrayList("Author");
            $this->Users->create($user);
        }
        $result = $this->DbManager->save();
        // Now it 's time to fake profiles
        return $this->fake_authors();
    }
    // fake authors will be called automaticaly
    public function fake_authors(): IActionResult
    {
        // this code is used to create a fake data
        $faker = Faker\Factory::create();
        $users = $this->DbManager->Users->toArray();
        // avatars
        $avatars = ["avatar1", "avatar2", "avatar3", "avatar4", "avatar5", "avatar6", "avatar7"];
        for ($j = 0; $j < count($users); $j++) {
            $author = new Author();
            $author->UserId = $users[$j]->Id;
            $author->Name = $faker->name();
            $author->Email = $users[$j]->Username;
            $author->Location = $faker->address();
            $author->Description = $faker->text(200);
            $author->Occupation = $faker->text(50);
            $author->Gender = $faker->text(20);
            $author->Phone = $faker->phoneNumber();
            $author->Photo = "/images/author/" . $avatars[mt_rand(0, 6)] . "png";
            $this->DbManager->Authors->add($author);
        }
        $this->DbManager->save();

        $authors = $this->DbManager->Authors;
        //var_dump($authors->toArray());
        return $this->content(count($authors->toArray()) . " Users and Authors faked");
    }

    public function index(): IActionResult
    {
        return $this->view();
    }

    public function post(): IActionResult
    {
        return $this->view();
    }
}
