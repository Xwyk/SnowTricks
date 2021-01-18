<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Media;
use App\Entity\User;
use App\Entity\Video;
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
     * @Route("/figure/add", name="figure_add")
     * @Route("/figure/{id}/edit", name="figure_edit")
     * @param Request $request
     * @param ObjectManager $manager
     * @param FigureRepository $repository
     * @param null $id
     * @return Response
     */
    public function figure(Request $request, ObjectManager $manager, FigureRepository $repository, $id = null): Response
    {
        $figure = new Figure();
        if ($id !== null){
            $figure = $repository->find($id);
        }
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $figure->setCreationDate(new \DateTime());
            $manager->persist($figure);

            foreach ($form->get('videos') as $videoForm){
                $video = new Video();
                $video->setFigure($figure)
                    ->setUrl($videoForm->get('url')->getData());
                $manager->persist($video);
            }
            $manager->flush();

            //return $this->redirectToRoute('figure_show', ['id'=>$figure->getId()]);
        }
        return $this->render("snowtricks/addFigure.html.twig", ["form" => $form->createView()]);
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
