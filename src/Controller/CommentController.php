<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Figure;
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
     * @Route("/figure/{id}-{slug}/moreComments/{start}", name="loadMoreComments")
     * @param Figure $figure
     * @param DBQueries $DBQueries
     * @param int $start
     * @return Response
     */
    public function loadMoreComments(Figure $figure, DBQueries $DBQueries, int $start): Response
    {
        $comments = $DBQueries->getNextCommmentsForFigure($figure,$start);
        return $this->render(
            "snowtricks/loadMoreComments.html.twig",
            ['comments' => $comments]
        );
    }

    /**
     * @Route("/figure/{id}-{slug}/comment/add", name="comment_add")
     * @param ObjectManager $manager
     * @param Figure $figure
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function addComment(ObjectManager $manager, Figure $figure, Request $request): Response
    {
        $comment = new Comment();
        $comment->setContent($request->request->get('content'));
        $comment->setAuthor($this->getUser());
        $figure->addComment($comment);
        $manager->persist($figure);
        $manager->flush();
        return $this->render("snowtricks/oneMoreComment.html.twig", ['comment' => $comment]);
    }
}
