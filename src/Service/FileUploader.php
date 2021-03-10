<?php

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader{

    protected $slugger;
    protected $params;
    protected $pictures_directory;
    protected $root_public_directory;
    public function __construct(SluggerInterface $slugger, ParameterBagInterface $params, string $root_public_directory, string $pictures_directory)
    {
        $this->params  = $params;
        $this->slugger = $slugger;
        $this->pictures_directory = $pictures_directory;
        $this->root_public_directory = $root_public_directory;
    }

    public function uploadPictures(ArrayCollection $pictures){
        foreach ($pictures as $picture){
            $pictureToUpload = $picture->getImage();
            if ($pictureToUpload) {
                $picture->setUrl($this->upload($pictureToUpload));
            }
        }
    }


    public function upload(File $pictureToUpload): string{
        $originalFilename = pathinfo($pictureToUpload->getClientOriginalName(), PATHINFO_FILENAME);

        $sluggedFilename = $this->slugger->slug($originalFilename);
        $newFilename = $sluggedFilename.'-'.uniqid().'.'.$pictureToUpload->guessExtension();

        $relativeDirectory = $this->pictures_directory;
        $absoluteDirectory = $this->root_public_directory.$relativeDirectory;
        // Move to picture directory
        try {
            $pictureToUpload->move(
                $absoluteDirectory,
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        // Set url attribute of picture element
        return $relativeDirectory.$newFilename;
    }
}