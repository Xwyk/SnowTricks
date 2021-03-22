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
        $categories = $DBQueries->getCategories();
        return $this->render("home/home.html.twig", [
            "figures" => $figures,
            "categories" => $categories
        ]);
    }

    /**
     * Get the 15 next tricks in the database and create a Twig file with them that will be displayed via Javascript
     *
     * @Route("/loadMoreFigures/{category}/{start}", name="loadMoreFigures", requirements={"start": "\d+"}, defaults={"category"=0}, methods={"GET"})
     * @ParamConverter("Category", options={"mapping": {"id": "categoru"}})
     * @param DBQueries $DBQueries
     * @param int $start
     * @param Category|null $category
     * @return Response
     */
    public function loadMoreFigures(DBQueries $DBQueries, int $start, Category $category = null)
    {
        // Get 15 tricks from the start position
        $figures = $DBQueries->getNextFigures($start, $category);
        if (empty($figures)){
            $this->addFlash('info','Toutes les figures sont chargées');
        }
        return $this->render('home/loadMoreFigures.html.twig', [
            'figures' => $figures
        ]);
    }
}