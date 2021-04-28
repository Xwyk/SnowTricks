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
    protected $rootPublicDirectory;

    /**
     * FileUploader constructor.
     * @param SluggerInterface $slugger
     * @param ParameterBagInterface $params
     * @param string $rootPublicDirectory
     * @param string $pictures_directory
     */
    public function __construct(SluggerInterface $slugger, ParameterBagInterface $params, string $rootPublicDirectory, string $pictures_directory)
    {
        $this->params  = $params;
        $this->slugger = $slugger;
        $this->pictures_directory = $pictures_directory;
        $this->rootPublicDirectory = $rootPublicDirectory;
    }

    /**
     * @param ArrayCollection $pictures
     */
    public function uploadPictures(ArrayCollection $pictures){
        foreach ($pictures as $picture){
            $pictureToUpload = $picture->getImage();
            if ($pictureToUpload) {
                $picture->setUrl($this->uploadPicture($pictureToUpload));
            }
        }
    }


    /**
     * @param File $pictureToUpload
     * @return string
     */
    public function uploadPicture(File $pictureToUpload): string{
        $originalFilename = pathinfo($pictureToUpload->getClientOriginalName(), PATHINFO_FILENAME);

        $sluggedFilename = $this->slugger->slug($originalFilename);
        $newFilename = $sluggedFilename.'-'.uniqid().'.'.$pictureToUpload->guessExtension();

        $relativeDirectory = $this->pictures_directory;
        $absoluteDirectory = $this->rootPublicDirectory.$relativeDirectory;
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