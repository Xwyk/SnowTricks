<?php

namespace App\Service;

use App\Entity\RegisterToken;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ApplicationMailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig )
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationMail(RegisterToken $token){
        $this->mailer->send(
            (new Email())
                ->from('hello@example.com')
                ->to($token->getUser()->getMailAddress())
                ->subject('Validez votre inscription')
                ->text('Validez votre inscription')
                ->html($this->twig->render('registration/confirmation_email.html.twig', [
                    'token' => $token
                ]))
        );
    }
}
