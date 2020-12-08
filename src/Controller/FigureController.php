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

    /**
     * @Route("/addTrick", name="figure_add")
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function addFigure(Request $request, ObjectManager $manager): Response
    {
        if (!($this->getUser())){
            return $this->redirectToRoute('app_login');
        }
        $figure = new Figure();
        $media = new Media();
        $media1 = new Media();
        $figure->addMedia($media);
        $figure->addMedia($media1);
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $figure->setCreationDate(new \DateTime())
                   ->setAuthor($this->getUser());
            foreach ($figure->getMedias() as $media){
                $media->setType(1);
                $media->setFigure($figure);
                $manager->persist($media);
            }
            $manager->persist($figure);
            $manager->flush();
            return $this->redirectToRoute('figure_show', ['id'=>$figure->getId()]);
       }
        return $this->render("snowtricks/addFigure.html.twig", ["form" => $form->createView()]);
    }
}
