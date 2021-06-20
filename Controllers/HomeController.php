<?php

namespace Application\Controllers;

use DevNet\System\Linq;
use DevNet\Core\Controller\AbstractController;
use DevNet\Core\Controller\IActionResult;
use DevNet\Core\Controller\Filters\HttpMethodFilter;
use DevNet\Entity\EntityContext;
use DevNet\Entity\EntitySet;
use Application\Models\ContactForm;

class HomeController extends AbstractController
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
