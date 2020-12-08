<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     * @Route("/figure/{id}", name="figure_show")
     */
    public function show(FigureRepository $repository, $id): Response
    {
        $figure = $repository->find($id);
        return $this->render("snowtricks/figure.html.twig", ["figure" => $figure]);
    }
}
