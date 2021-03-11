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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param DBQueries $DBQueries
     * @return Response
     */
    public function home(DBQueries $DBQueries): Response
    {
        $this->addFlash('info','Ceci est une info');
        $this->addFlash('success','Ceci est une validation');
        $this->addFlash('danger','Ceci est une erreur');
        $this->addFlash('warning','Ceci est un avertissement');

        $figures = $DBQueries->getLastFigures();
        return $this->render("home/home.html.twig", ["figures" => $figures]);
    }

    /**
     * Get the 15 next tricks in the database and create a Twig file with them that will be displayed via Javascript
     *
     * @Route("/loadMoreFigures/{start}", name="loadMoreFigures", requirements={"start": "\d+"})
     * @param DBQueries $DBQueries
     * @param int $start
     * @return Response
     */
    public function loadMoreFigures(DBQueries $DBQueries, int $start)
    {
        // Get 15 tricks from the start position
        $figures = $DBQueries->getNextFigures($start);
        if (empty($figures)){
            $this->addFlash('info','Toutes les figures sont chargées');
        }
        return $this->render('home/loadMoreFigures.html.twig', [
            'figures' => $figures
        ]);
    }
}