<?php

namespace App\Controller;

use App\Entity\RegisterToken;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Manager\UserManager;
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
     * @Route("/register", name="app_register", methods={"GET", "POST"})
     * @param Request $request
     * @param UserManager $userManager
     * @return Response
     */
    public function register(Request $request, UserManager $userManager): Response
    {
        if ($this->getUser()){
            $this->addFlash('info', 'Vous êtes déjà connecté en tant que '.$this->getUser()->getUsername().'');
            return $this->redirectToRoute('home');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userManager->register($user, $form->get('plainPassword')->getData());
            $this->addFlash('info', 'Le compte a été créé, merci de vérifier vos mails afin valider ce dernier');

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/{token}", name="app_verify_email", methods={"GET"})
     * @ParamConverter("RegisterToken", options={"mapping": {"token": "token"}})
     * @param UserManager $userManager
     * @param RegisterToken $registerToken
     * @return Response
     */
    public function verifyUserEmail(UserManager $userManager, RegisterToken $registerToken): Response
    {
        if ($userManager->verifyUser($registerToken)){
            $this->addFlash('success', 'Le compte a été vérifié, vous pouvez vous connecter');
            return $this->redirectToRoute('app_login');
        }
        $this->addFlash('danger', 'La validation n\'a pas pu être effectuée, lien expiré');
        return $this->redirectToRoute('home');
    }
}
