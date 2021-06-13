<?php

namespace Application\Controllers;

use Application\Models\Author;
use Application\Models\Comment;
use Application\Models\Post;
use Application\Models\Section;
use Application\Models\User;
use DevNet\Entity\EntityContext;
use DevNet\System\Collections\ArrayList;
use DevNet\Web\Identity\UserManager;
use DevNet\Web\Mvc\Controller;
use DevNet\Web\Mvc\IActionResult;
use Faker;

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
        return $this->view();
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
        return $this->content(count($authors->toArray()) . " Users and Authors faked");
    }
    //we create somme sections
    public function fake_sections(): IActionResult
    {
        // this code is used to create a fake data
        $faker = Faker\Factory::create();

        // sections
        $sectitles = ["News", "Events", "Trending", "Medecine", "Technology", "Sports"];
        for ($j = 0; $j < count($sectitles); $j++) {
            $section = new Section();
            $section->Title = $sectitles[$j];
            $section->Slug = $section->Title;
            $section->Image = "";
            $section->Description = $faker->text(100);
            $this->DbManager->Sections->add($section);
        }
        $this->DbManager->save();
        return $this->content(count($this->DbManager->Sections->toArray()) . " Sections faked ...");
    }
    // we add some faked posts
    public function fake_posts(): IActionResult
    {
        // this code is used to create a fake data
        $faker = Faker\Factory::create();

        // for each section multiple posts
        $sections = $this->DbManager->Sections->toArray();

        $posts = $this->DbManager->Posts;
        // loop to create fake data

        for ($j = 0; $j < count($sections); $j++) {
            // for each section create a random number of posts
            for ($i = 0; $i < mt_rand(1, 10); $i++) {
                $post = new Post();
                $post->SectionId = $sections[$j]->Id;
                $post->AuthorId = mt_rand(1, 3);
                $post->Title = $faker->text(40);
                $post->Slug = $post->Title;
                $post->Excerpt = $faker->text(50);
                $post->Content = $faker->text(200);
                $post->Image = "https://picsum.photos/200/300";
                $post->EditedAt = $this->randomDate('2021-01-01', '2021-06-10');
                $this->DbManager->Posts->add($post);
            }
            $this->DbManager->save();
        }
        return $this->content(count($this->DbManager->Posts->toArray()) . " posts faked ...");
    }

    public function fake_comments(): IActionResult
    {
        // this code is used to create a fake data
        $faker = Faker\Factory::create();

        // for each post make several comments
        $posts = $this->DbManager->Posts->toArray();
        for ($j = 0; $j < count($posts); $j++) {
            for ($i = 0; $i < mt_rand(1, 6); $i++) {
                $comment = new Comment();
                $comment->PostId = $posts[$j]->Id;
                $comment->AuthorId = mt_rand(1, 20);
                $comment->Content = $faker->text(200);;
                $this->DbManager->Comments->add($comment);
            }
        }
        $this->DbManager->save();
        return $this->content(count($this->DbManager->Comments->toArray()) . " comments faked");
    }

    public function global_feed(): IActionResult
    {
        // this code is used to create a fake data
        $this->create_admin();
        $this->fake_users();
        $this->fake_sections();
        $this->fake_posts();
        $this->fake_comments();
    }

    function randomDate($start_date, $end_date)
    {
        // Convert to timetamps
        $min = strtotime($start_date);
        $max = strtotime($end_date);

        // Generate random number using above bounds
        $val = rand($min, $max);

        // Convert back to desired date format
        return Date('Y-m-d H:m:s', $val);
    }
}
