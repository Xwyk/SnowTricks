<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PhpParser\Node\Expr\New_;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");
        $user = new User();
        $user->setPseudo($faker->firstNameMale)
             ->setMailAddress($faker->freeEmail)
             ->setPassword($faker->password )
             ->setCreationDate($faker->dateTimeBetween("-30 days"));
        $manager->persist($user);
        for ($i = 0; $i < rand(10, 20); $i++){
            $figure = new Figure();
            $figure->setAuthor($user)
                   ->setName(join($faker->words, ' '))
                   ->setDescription(join($faker->paragraphs, ' '))
                   ->setCreationDate($faker->dateTimeBetween("-30 days"));
            for ($a=0; $a < rand(3, 6); $a++){
                $media = new Media();
                $media->setUrl($faker->imageUrl())
                      ->setFigure($figure)
                      ->setType(1);
                $manager->persist($media);
            }
            for ($a=0; $a < rand(3, 6); $a++){
                $media = new Media();
                $media->setUrl($faker->imageUrl())
                    ->setFigure($figure)
                    ->setType(2);
                $manager->persist($media);
            }
            for ($j = 0; $j < rand(5,10); $j++){
                $comment = new Comment();
                $comment->setAuthor($user)
                        ->setFigure($figure)
                        ->setCreationDate($faker->dateTimeBetween('-'.$figure->getCreationDate()->diff(new \DateTime())->days.' days'))
                        ->setContent(join($faker->paragraphs, ' '));
                $manager->persist($comment);
            }
            $manager->persist($figure);
        }
        $manager->flush();
    }
}
