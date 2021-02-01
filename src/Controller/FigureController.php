<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Media;
use App\Entity\Picture;
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
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function add(Request $request, ObjectManager $manager): Response
    {
        $form = $this->createForm(FigureType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $figure = $form->getData();
            $manager->persist($figure);
            $manager->flush();

            return $this->redirectToRoute('figure_show', ['id'=>$figure->getId(), 'slug'=>$figure->getSlug()]);
        }
        return $this->render("snowtricks/addFigure.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/figure/{id}-{slug}/edit", name="figure_edit")
     * @param Request $request
     * @param ObjectManager $manager
     * @param Figure $figure
     * @return Response
     */
    public function edit(Request $request, ObjectManager $manager, Figure $figure): Response
    {

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            foreach ($form->get('pictures') as $pictureForm){
                $image = $pictureForm->get('image')->getData();
                if($image){
                    $picture = $pictureForm->getData();
                    $picture->setUrl($image->getPathname());
                }
            }
            $manager->persist($figure);
            $manager->flush();
            return $this->redirectToRoute('figure_show', ['id'=>$figure->getId(), 'slug'=>$figure->getSlug()]);
        }
        return $this->render("snowtricks/addFigure.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/figure/{id}-{slug}", name="figure_show")
     * @param Figure $figure
     * @return Response
     */
    public function showOne(Figure $figure): Response
    {
        return $this->render("snowtricks/figure.html.twig", ["figure" => $figure]);
    }
}
