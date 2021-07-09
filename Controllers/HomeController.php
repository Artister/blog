<?php

namespace Application\Controllers;

use DevNet\System\Linq;
use DevNet\Core\Controller\AbstractController;
use DevNet\Core\Controller\IActionResult;
use DevNet\Core\Controller\Filters\HttpMethodFilter;
use DevNet\Entity\EntityContext;
use DevNet\Entity\EntitySet;
use Application\Models\ContactForm;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

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

        //Create a new PHPMailer instance
        $mail = new PHPMailer();
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        //SMTP::DEBUG_OFF = off (for production use)
        //SMTP::DEBUG_CLIENT = client messages
        //SMTP::DEBUG_SERVER = client and server messages
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        //Use `$mail->Host = gethostbyname('smtp.gmail.com');`
        //if your network does not support SMTP over IPv6,
        //though this may cause issues with TLS
        //Set the SMTP port number:
        // - 465 for SMTP with implicit TLS, a.k.a. RFC8314 SMTPS or
        // - 587 for SMTP+STARTTLS
        $mail->Port = 465;
        //Set the encryption mechanism to use:
        // - SMTPS (implicit TLS on port 465) or
        // - STARTTLS (explicit TLS on port 587)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = 'bmaconsultingnet@gmail.com';
        //Password to use for SMTP authentication
        $mail->Password = 'Bma20202020bma';
        //Set who the message is to be sent from
        //Note that with gmail you can only use your account address (same as `Username`)
        //or predefined aliases that you have configured within your account.
        //Do not use user-submitted addresses in here
        $mail->setFrom('bmaconsultingnet@yahoo.com', 'DevNet Support Team');
        //Set an alternative reply-to address
        //This is a good place to put user-submitted addresses
        $mail->addReplyTo('mahfoud_bousba@yahoo.com', 'mahfoud bousba yahoo');
        //Set who the message is to be sent to
        $mail->addAddress($form->Email, $form->Name);
        //Set the subject line
        $mail->Subject = 'PHPMailer GMail SMTP test';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        $htmlmessage = "<h4>Hi we take care of your request..We gonna contact you soon....</h4>"
            . "<br>---------- Your original message ----------"
            . "<br><p>{$form->Message}</p>";
        $mail->msgHTML($htmlmessage);
        //Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors
        if (!$mail->send()) {
            return $this->content('Mailer Error: ' . $mail->ErrorInfo);
        } else {
            return $this->content('Your request sent successfully.. ');
        }
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
