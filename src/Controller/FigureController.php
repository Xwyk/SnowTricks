<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\Media;
use App\Entity\Picture;
use App\Entity\User;
use App\Entity\Video;
use App\Form\CommentType;
use App\Form\FigureType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\FigureRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
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
    public function add(Request $request, ObjectManager $manager, SluggerInterface $slugger): Response
    {
        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // For each picture form in form['pictures'], get uploaded image file,
            // copy it to public folder and set url attribute value
            foreach ($form->get('pictures')->getData() as $picture) {
                $pictureToUpload = $picture->getImage();
                if ($pictureToUpload) {
                    $picture->setUrl($this->uploadPicture($pictureToUpload, $slugger));
                }
            }
            // Create figure from form
            $figure = $form->getData();
            // Persist element in database
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
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function edit(Request $request, ObjectManager $manager, Figure $figure, CategoryRepository $catRepo, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // For each picture form in form['pictures'], get uploaded image file,
            // copy it to public folder and set url attribute value
            foreach ($form->get('pictures')->getData() as $picture) {
                $pictureToUpload = $picture->getImage();
                if ($pictureToUpload) {
                    $picture->setUrl($this->uploadPicture($pictureToUpload, $slugger));
                }
            }
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
     * @param CommentRepository $commentRepo
     * @return Response
     */
    public function showOne(Figure $figure, CommentRepository $commentRepo): Response
    {


        return $this->render("snowtricks/figure.html.twig", [
            "figure" => $figure,
            'comments' => $commentRepo->findBy(['figure' => $figure],
            [
                'creationDate' => 'DESC'
            ],
            5,
            0
            )
        ]);
    }

    protected function uploadPicture(File $pictureToUpload, SluggerInterface $slugger){
            $originalFilename = pathinfo($pictureToUpload->getClientOriginalName(), PATHINFO_FILENAME);
            // slug filename to keep original name as part of the final name,
            // and add an uuid to name
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureToUpload->guessExtension();

            // Move to picture directory
            try {
                $pictureToUpload->move(
                    $this->getParameter('root_public_directory').
                    $this->getParameter('pictures_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            // Set url attribute of picture element
            return $this->getParameter('pictures_directory').$newFilename;
    }
}
