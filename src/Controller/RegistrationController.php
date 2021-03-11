<?php

namespace App\Controller;

use App\Entity\RegisterToken;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\ApplicationMailer;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

//            $appMailer->sendConfirmationMailTo(
//                $user,
//                $this->renderView('registration/confirmation_email.html.twig', [
//                    'token' => $token
//                ])
//            );

            $manager->persist($token);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('info','Le compte a été créé, merci de vérifier vos mails afin valider ce dernier');

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/{token}", name="app_verify_email")
     * @ParamConverter("RegisterToken", options={"mapping": {"token": "token"}})
     * @param ObjectManager $manager
     * @param RegisterToken $registerToken
     * @return Response
     */
    public function verifyUserEmail(ObjectManager $manager, RegisterToken $registerToken): Response
    {
        $origin = $registerToken->getValidityDate();
        $target = new \DateTime();
        $interval = $origin->diff($target);
        if ($interval->invert){
            $manager->persist($registerToken->getUser()->setIsVerified(true));
            $manager->remove($registerToken);
            $manager->flush();
            $this->addFlash('success','Le compte a été vérifié, vous pouvez vous connecter');
            return $this->redirectToRoute('app_login');
        }
        $this->addFlash('danger','La validation n\'a pas pu être effectuée, lien expiré');
        return $this->redirectToRoute('home');
    }
}
