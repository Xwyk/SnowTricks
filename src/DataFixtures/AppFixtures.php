<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

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
        for ($i = 1; $i < 5; $i++){
            $figure = new Figure();
            $figure->setAuthor($user)
                   ->setName(join($faker->words, ' '))
                   ->setDescription(join($faker->paragraphs, ' '))
                   ->setCreationDate($faker->dateTimeBetween("-30 days"));
            for ($j = 1; $j < rand(4,6); $j++){
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
