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
use App\Service\DBQueries;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     * @param FigureRepository $figureRepository
     * @return Response
     */
    public function home(FigureRepository $figureRepository): Response
    {
        $figures = $figureRepository->getLastFigures();
        return $this->render("home/home.html.twig", [
            "figures" => $figures
        ]);
    }

    /**
     * Get the 15 next tricks in the database and create a Twig file with them that will be displayed via Javascript
     *
     * @Route("/loadMoreFigures/{start}", name="loadMoreFigures", requirements={"start": "\d+"}, methods={"GET"})
     * @param FigureRepository $figureRepository
     * @param int $start
     * @return Response
     */
    public function loadMoreFigures(FigureRepository $figureRepository, int $start)
    {
        // Get 15 tricks from the start position
        $figures = $figureRepository->getNextFigures($start);
        if (empty($figures)){
            $this->addFlash('info','Toutes les figures sont chargées');
        }
        return $this->render('home/loadMoreFigures.html.twig', [
            'figures' => $figures
        ]);
    }
}