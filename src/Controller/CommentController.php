<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Form\CommentType;
use App\Form\FigureType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Service\DBQueries;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/figure/{id}-{slug}/moreComments/{start}", name="loadMoreComments", methods={"POST"})
     * @param Figure $figure
     * @param CommentRepository $commentRepository
     * @param int $start
     * @return Response
     */
    public function loadMoreComments(Figure $figure, CommentRepository $commentRepository, int $start): Response
    {
        $comments = $commentRepository->getNextCommmentsForFigure($figure,$start);
        return $this->render(
            "comment/loadMoreComments.html.twig",
            ['comments' => $comments]
        );
    }

    /**
     * @Route("/figure/{id}-{slug}/comment/add", name="comment_add", methods={"POST"})
     * @param ObjectManager $manager
     * @param Figure $figure
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function addComment(ObjectManager $manager, Figure $figure, Request $request): Response
    {
        // TODO verifier le contenu de content
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $comment->setAuthor($this->getUser());
            $figure->addComment($comment);
            $manager->persist($figure);
            $manager->flush();
            return $this->render("comment/loadMoreComments.html.twig", ['comments' => array($comment)]);
        }
        return new Response("bite");

    }
}
