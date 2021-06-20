<?php

namespace Application\Controllers;

use DevNet\Entity\EntityContext;
use DevNet\Core\Controller\AbstractController;
use DevNet\Core\Controller\IActionResult;
use Application\Models\ContactForm;

class HomeController extends AbstractController
{
    public function __construct(EntityContext $dbManager)
    {
        $this->DbManager = $dbManager;
    }

    public function index() : IActionResult
    {
        return $this->view();
    }

    public function about() : IActionResult
    {
        return $this->view();
    }

    public function contact(ContactForm $form) : IActionResult
    {
        if (!$form->isValide())
        {
            return $this->view();
        }

        $headers = "From: noreply@yourdomain.com\n";
        $headers .= "Reply-To: {$form->Email}";
        $to = 'yourname@yourdomain.com';
        $subject = "Website Contact Form:  {$form->Subject}";
        $message = "You have received a new message from your website contact form\n";
        $message .= "Here are the details:\n";
        $message .= "Name: {$form->Name}\n";
        $message .= "Email: {$form->Email}\n";
        $message .= "Message: {$form->Message}";
   
        $success = mail($to, $subject, $message, $headers);

        if ($success)
        {
            $this->ViewData['response'] = "your message has been sent successfully";
        }
        else
        {
            $this->ViewData['response'] = "Sorry your message wasn't delivered!";
        }

        return $this->content("You message has been sent.");
    }

    public function error() : IActionResult
    {
        $error = new \Exception("Sorry! Looks like this page doesn't exist.", 404);
        if($this->HttpContext->Error)
        {
            switch ($this->HttpContext->Error->getCode())
            {
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
