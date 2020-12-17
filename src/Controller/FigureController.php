<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Media;
use App\Entity\User;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param FigureRepository $repository
     * @return Response
     */
    public function showAll(FigureRepository $repository): Response
    {
        $figures = $repository->findAll();
        return $this->render("snowtricks/home.html.twig", ["figures" => $figures]);
    }

    /**
     * @Route("/figure/{id}", name="figure_show")
     * @param FigureRepository $repository
     * @param $id
     * @return Response
     */
    public function showOne(FigureRepository $repository, $id): Response
    {

        $figure = $repository->find($id);
        return $this->render("snowtricks/figure.html.twig", ["figure" => $figure]);
    }


}
