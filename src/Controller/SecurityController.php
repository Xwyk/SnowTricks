<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\RegisterToken;
use App\Entity\ResetPasswordToken;
use App\Entity\User;
use App\Form\FigureType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
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
     * @Route("/reset", name="app_ask_for_reset", methods={"GET", "POST"})
     * @param Request $request
     * @param UserManager $userManager
     * @return Response
     */
    public function askForReset(Request $request, UserManager $userManager): Response
    {
        if ($this->getUser()){
            $this->addFlash('info', 'Vous êtes déjà connecté en tant que '.$this->getUser()->getUsername().'');
            return $this->redirectToRoute('home');
        }
        $pseudo = $request->request->get('pseudo');
        if (!$pseudo){
            return $this->render('security/reset.html.twig');
        }
        $userManager->sendReset($pseudo);
        $this->addFlash('info', 'Un mail de réinitialisation vous a été envoyé.');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/reset/{token}", name="app_reset", methods={"GET", "POST"})
     * @ParamConverter("ResetPasswordToken", options={"mapping": {"token": "token"}})
     * @param ResetPasswordToken|null $resetPasswordToken
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function resetPassword(ObjectManager $manager, Request $request, ResetPasswordToken $resetPasswordToken = null): Response
    {
        if ($this->getUser()){
            $this->addFlash('info', 'Vous êtes déjà connecté en tant que '.$this->getUser()->getUsername().'');
            return $this->redirectToRoute('home');
        }

        $tempUser = new User();
        $form = $this->createForm(ResetPasswordType::class, $tempUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $user = $resetPasswordToken->getUser();
           $user->setPassword((password_hash($form->get('plainPassword')->getData(), PASSWORD_BCRYPT )));
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('info', 'VOtre mot de passe à été enregistré, merci de vous reconnecter');
            $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);


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
