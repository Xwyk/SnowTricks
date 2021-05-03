<?php

namespace App\Service;

use App\Entity\RegisterToken;
use App\Entity\ResetPasswordToken;
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
                ->from('contact@snowtricks.fr')
                ->to($token->getUser()->getMailAddress())
                ->subject('Validez votre inscription')
                ->text('Validez votre inscription')
                ->html($this->twig->render('registration/confirmation_email.html.twig', [
                    'token' => $token
                ]))
        );
    }

    public function sendResetMail(ResetPasswordToken $token)
    {
        $this->mailer->send(
            (new Email())
                ->from('contact@snowtricks.fr')
                ->to($token->getUser()->getMailAddress())
                ->subject('Réinitialisez votre mot de passe')
                ->text('Réinitialisez votre mot de passe')
                ->html($this->twig->render('security/reset_email.html.twig', [
                    'token' => $token
                ]))
        );
    }
}
