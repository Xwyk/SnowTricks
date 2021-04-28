<?php


namespace App\EntityListener;


use App\Entity\Picture;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PictureListener
{

    private string $rootPublicDirectory;

    public function __construct(string $rootPublicDirectory)
    {
        $this->rootPublicDirectory = $rootPublicDirectory;
    }



    public function postRemove(Picture $picture, LifecycleEventArgs $event)
    {
        unlink($this->rootPublicDirectory.$picture->getUrl());
    }
}