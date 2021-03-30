<?php

namespace App\Controller;

use App\Entity\RegisterToken;
use App\Entity\ResetPasswordToken;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login", methods={"GET", "POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    /**
     * @Route("/verify/{token}", name="app_verify_email", methods={"GET"})
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

    /**
     * @Route("/reset/{token}", name="app_reset", methods={"GET", "POST"}, defaults={"token": null})
     * @ParamConverter("ResetPasswordToken", options={"mapping": {"token": "token"}})
     * @param Request $request
     * @param UserManager $userManager
     * @param UserRepository $userRepository
     * @param ResetPasswordToken|null $resetPasswordToken
     * @return Response
     */
    public function reset(Request $request, UserManager $userManager, UserRepository $userRepository, ResetPasswordToken $resetPasswordToken = null): Response
    {
        if ($this->getUser()){
            $this->addFlash('info', 'Vous êtes déjà connecté en tant que '.$this->getUser()->getUsername().'');
            return $this->redirectToRoute('home');
        }
        if (!$resetPasswordToken){
            $pseudo = $request->request->get('pseudo');

            if (!$pseudo){
                return $this->render('security/reset.html.twig');
            }
            $userManager->sendReset($pseudo);
            $this->addFlash('info', 'Un mail de réinitialisation vous a été envoyé.');
            return $this->redirectToRoute('home');
        }
        dd($resetPasswordToken);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function logout()
    {
        $this->addFlash('info','Déconnexion effectuée, merci de votre visite');
        return $this->redirectToRoute('home');
    }
}
