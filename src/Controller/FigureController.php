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
use App\Repository\CommentRepository;
use App\Repository\FigureRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureController extends AbstractController
{
    /**
     * @Route("/figure/add", name="figure_add")
     * @param Request $request
     * @param ObjectManager $manager
     * @param CategoryRepository $catRepo
     * @return Response
     */
    public function add(Request $request, ObjectManager $manager, CategoryRepository $catRepo, SluggerInterface $slugger): Response
    {
        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure,['entityManager' => $catRepo]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->get('pictures')->getData());
            foreach ($form->get('pictures')->getData() as $picture){
                if ($pictureToUpload) {
                    $originalFilename = pathinfo($pictureToUpload->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureToUpload->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $pictureToUpload->move(
                            $this->getParameter('pictures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    //$pictureForm->get('url')->setData($this->getParameter('pictures_directory').$newFilename);
//                    $figure->setBrochureFilename($newFilename);
                }
            }
            $figure = $form->getData();
            $manager->persist($figure);
            $manager->flush();


            return $this->redirectToRoute('figure_show', ['id' => $figure->getId(), 'slug' => $figure->getSlug()]);
        }
        return $this->render("snowtricks/addFigure.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/figure/{id}-{slug}/edit", name="figure_edit")
     * @param Request $request
     * @param ObjectManager $manager
     * @param Figure $figure
     * @param CategoryRepository $catRepo
     * @return Response
     */
    public function edit(Request $request, ObjectManager $manager, Figure $figure, CategoryRepository $catRepo): Response
    {
        $form = $this->createForm(FigureType::class, $figure, ['entityManager' => $catRepo]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($figure);
            $manager->flush();
            return $this->redirectToRoute('figure_show', ['id' => $figure->getId(), 'slug' => $figure->getSlug()]);
        }
        return $this->render("snowtricks/editFigure.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/figure/{id}-{slug}/delete", name="figure_delete")
     * @param ObjectManager $manager
     * @param Figure $figure
     * @return Response
     */
    public function delete(ObjectManager $manager, Figure $figure): Response
    {
        $manager->remove($figure);
        $manager->flush();
    }

    /**
     * @Route("/figure/{id}-{slug}", name="figure_show")
     * @param Figure $figure
     * @return Response
     */
    public function showOne(Figure $figure, CommentRepository $commentRepo): Response
    {
        return $this->render("snowtricks/figure.html.twig", ["figure" => $figure, 'comments' => $commentRepo->findBy(['figure' => $figure], ['creationDate' => 'DESC'], 5, 0)]);
    }

    /**
     * @Route("/figure/{id}-{slug}/{start}", name="loadMoreComments")
     * @param Figure $figure
     * @param CommentRepository $commentRepo
     * @param int $start
     * @return Response
     */
    public function loadMoreComments(Figure $figure, CommentRepository $commentRepo, int $start): Response
    {
        return $this->render("snowtricks/loadMoreComments.html.twig", ['comments' => $commentRepo->findBy(['figure' => $figure], ['creationDate' => 'DESC'], 5, $start)]);
    }
}
