<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setPseudo("elzobo")
            ->setMailAddress("florianleboul@gmail.com")
            ->setPassword("titi")
            ->setCreationDate(new \DateTime());
        $manager->persist($user);
        $figure = new Figure();
        $figure->setAuthor($user)
               ->setName("backflip")
               ->setDescription("Retournée à la volée")
               ->setCreationDate(new \DateTime());
        $manager->persist($figure);
        $manager->flush();
    }
}
