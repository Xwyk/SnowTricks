<?php

namespace App\Controller;

use App\Entity\RegisterToken;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\RegisterTokenRepository;
use App\Security\EmailVerifier;
use App\Service\ApplicationMailer;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{


    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ObjectManager $manager
     * @param ApplicationMailer $appMailer
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, ObjectManager $manager, ApplicationMailer $appMailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $token = new RegisterToken();
            $token->setUser($user);

            $appMailer->sendConfirmationMailTo(
                $user,
                $this->renderView('registration/confirmation_email.html.twig', [
                    'token' => $token
                ])
            );

            $manager->persist($token);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/{token}", name="app_verify_email")
     * @ParamConverter("RegisterToken", options={"mapping": {"token": "token"}})
     * @param Request $request
     * @param RegisterTokenRepository $tokenRepo
     * @param string $token
     * @param RegisterToken $tok
     * @return Response
     */
    public function verifyUserEmail(Request $request, RegisterTokenRepository $tokenRepo, string $token, RegisterToken $tok): Response
    {
        $t=$tokenRepo->findOneBy(['token' => $token]);
        dd($t);

        return $this->redirectToRoute('home');
    }
}
