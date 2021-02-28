<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/figure/{id}-{slug}/{start}", name="loadMoreComments")
     * @param Figure $figure
     * @param CommentRepository $commentRepo
     * @param int $start
     * @return Response
     */
    public function loadMoreComments(Figure $figure, CommentRepository $commentRepo, int $start): Response
    {
        $comments = $commentRepo->findBy(['figure' => $figure]);
        return $this->render("snowtricks/loadMoreComments.html.twig",
            [
                'comments' => $comments,
                [
                    'creationDate' => 'DESC'
                ],
                $this->getParameter('figure_comment_step'),
                $start
            ]
        );
    }

    /**
     * @Route("/figure/{id}-{slug}/comment/add", name="comment_add")
     * @ParamConverter("figure", options={"id" = "id"})
     * @param ObjectManager $manager
     * @param Figure $figure
     * @param Request $reque
     * @return Response
     */
    public function addComment(ObjectManager $manager, Figure $figure, Request $request): Response
    {
        dd($figure);
        $comment = new Comment();
        $comment->setContent($request->request->get('content'));
        $figure->addComment($comment);
        $manager->persist($figure);
        $manager->flush();
        return $this->render("snowtricks/oneMoreComment.html.twig", ['comment' => $comment]);
    }
}
