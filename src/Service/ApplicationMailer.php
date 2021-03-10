<?php

namespace App\Service;

use App\Entity\RegisterToken;
use App\Entity\User;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class ApplicationMailer extends Mailer
{
    public function sendConfirmationMailTo(User $user, string $text){
        $email = (new Email())
            ->from('hello@example.com')
            ->to($user->getMailAddress())
            ->subject('Validez votre inscription')
            ->text('Validez votre inscription')
            ->html($text);

        $this->send($email);
    }
}
