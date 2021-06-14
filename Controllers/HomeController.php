<?php

namespace Application\Controllers;

// use Application\Models\Author;
// use Application\Models\Comment;
// use Application\Models\Post;
// use Application\Models\Section;
use DevNet\Entity\EntityContext;
use DevNet\Entity\EntitySet;
use DevNet\Web\Mvc\Controller;
use DevNet\Web\Mvc\IActionResult;
use DevNet\System\Linq;
use DevNet\Web\Mvc\Filters\HttpMethodFilter;

// use DateTime;
// use Faker;

class HomeController extends Controller
{
    private EntityContext $DbManager;
    private EntitySet $Posts;

    public function __construct(EntityContext $dbManager)
    {
        $this->DbManager = $dbManager;
        //$this->Filter('index', HttpMethodFilter::class, ['GET']);
    }

    public function index(): IActionResult
    {
        $sections = $this->DbManager->Sections;
        $this->ViewData['sections'] = $sections->toArray();
        // for recent posts
        $posts = $this->DbManager->Posts;
        // posts counter
        $offset = count($posts->toArray());
        $this->ViewData['recents'] = $posts->skip($offset - 6)->take(6)->toArray();
        // featured posts
        $featured = $this->DbManager->Posts->where(fn ($post) => $post->Featured == '1');
        $this->ViewData['featured'] = $featured->toArray();
        // display view
        return $this->view($sections);
    }

    public function blog(): IActionResult
    {

        $sections = $this->DbManager->Sections;
        $this->ViewData['sections'] = $sections->toArray();

        $posts = $this->DbManager->Posts;
        $this->ViewData['posts'] = $posts->orderByDescending(fn ($p) => $p->Id)->take(5);

        $comments = $this->DbManager->Comments;

        $this->ViewData['comments'] = $comments->orderByDescending(fn ($c) => $c->Id)->take(6);

        $this->ViewData['page'] = 1;

        return $this->View();
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


        return $this->View("Home/Blog");
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
    public function contact(ContactForm $form): IActionResult
    {
        if (!$form->isValide()) {
            return $this->view();
        }

        $headers = "From: mahfoud_bousba@yahoo.com\n";
        $headers .= "Reply-To: {$form->Email}";
        $to = 'mahfoudbousba2@gmail.com';
        $subject = "Website Contact Form:  {$form->Subject}";
        $message = "You have received a new message from your website contact form\n";
        $message .= "Here are the details:\n";
        $message .= "Name: {$form->Name}\n";
        $message .= "Email: {$form->Email}\n";
        $message .= "Message: {$form->Message}";

        $success = mail($to, $subject, $message, $headers);

        if ($success) {
            $this->ViewData['response'] = "your message has been sent successfully";
        } else {
            $this->ViewData['response'] = "Sorry your message wasn't delivered!";
        }

        return $this->content("You message has been sent.");
    }

    public function error(): IActionResult
    {
        $error = new \Exception("Sorry! Looks like this page doesn't exist.", 404);
        if ($this->HttpContext->Error) {
            switch ($this->HttpContext->Error->getCode()) {
                case 404:
                    break;
                case 403:
                    $error = new \Exception("Sorry! Your request is not allowed.", 403);
                    break;
                default:
                    $error = new \Exception("Woops! Looks like something went wrong.", 500);
                    break;
            }
        }

        return $this->view($error);
    }
}
