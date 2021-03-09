<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\Media;
use App\Entity\Picture;
use App\Entity\User;
use App\Entity\Video;
use App\Form\FigureType;
use App\Repository\CategoryRepository;
use App\Repository\FigureRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param FigureRepository $repository
     * @return Response
     */
    public function home(FigureRepository $repository): Response
    {
        $figures = $repository->findBy([], ['creationDate' => 'DESC'], 12, 0);
        return $this->render("snowtricks/home.html.twig", ["figures" => $figures]);
    }

    /**
     * Get the 15 next tricks in the database and create a Twig file with them that will be displayed via Javascript
     *
     * @Route("/{start}", name="loadMoreFigures", requirements={"start": "\d+"})
     */
    public function loadMoreFigures(FigureRepository $repo, $start)
    {
        // Get 15 tricks from the start position
        $figures = $repo->findBy([], ['creationDate' => 'DESC'], 12, $start);

        return $this->render('snowtricks/loadMoreFigures.html.twig', [
            'figures' => $figures
        ]);
    }
}