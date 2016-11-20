<?php

namespace App\Http\Controllers;

use App\User;

use App\Http\Controllers\UserController;

use SendGrid;
use SendGrid\Client;

class Mail extends Controller
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var
     */
    protected $template_id;

    /**
     * @var
     */
    protected $message;

    /**
     * Mail constructor.
     * @param User $user
     */
    public function __construct(UserController $user){
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function send_mail() {

        $mail_api = env('SEND_GRID');
        // name && email
        $from = new SendGrid\Email('Team', "noreply@mycommunityclassroom.com");

        $subject = "Welcome to My Community Classroom";
        // Name && Email
        $to = new SendGrid\Email('Name', "test@example.com");

        // @todo needs to be customized based upon type of mail we are sending
        $content = new SendGrid\Content("text/plain", "Hello, Email!");

        $mail = new SendGrid\Mail($from, $subject, $to, $content);

        // @todo needs to be customized based upon type of mail we are sending
        $mail->setTemplateId("13b8f94f-bcae-4ec6-b752-70d6cb59f932");

        $apiKey = $mail_api;

        $sg = new \SendGrid($apiKey);

        $response = $sg->client->mail()->send()->post($mail);
        $res = array(
            'status_code' => $response->statusCode(),
            'headers'     => $response->headers(),
            'body'        => $response->body(),
        );
        return json_encode($res);
    }
}