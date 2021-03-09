<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\Picture;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");

        // Create fake users
        $users = array();
        for ($i = 1; $i <= 4; $i++){
            $users['user'.$i] = (new User())->setPseudo('user'.$i)
                ->setPassword((password_hash('user'.$i, PASSWORD_BCRYPT )))
                ->setIsVerified(true)
                ->setMailAddress('user'.$i.'@snowtricks.fr')
                ->setCreationDate($faker->dateTimeBetween("-90 days", "-31 days"));
            $manager->persist($users['user'.$i]);
        }

        // Create fake categories
        $categories = array();
        $names      = array("Les grabs", "Les rotations", "Les flips", "Les rotations désaxées", "Les slides", "Les one foot tricks","Old school");
        for ($i=0; $i < count($names); $i++) {
            $categories[$names[$i]] = new Category();
            $categories[$names[$i]]->setName($names[$i]);
            $manager->persist($categories[$names[$i]]);
        }
        $figure = new Figure();
        $figure->setName("Seatbelt")
            ->setDescription("")
            ->setCreationDate($faker->dateTimeBetween("-30 days"))
            ->setCategory($categories["Les grabs"])
            ->addPicture((new Picture())->setUrl("/img/full/Seatbelt.jpg"))
            ->addPicture((new Picture())->setUrl("/img/full/Seatbelt.jpg"))
            ->addPicture((new Picture())->setUrl("/img/full/Seatbelt.jpg"))
            ->addVideo((new Video())->setUrl("https://www.youtube.com/watch?v=4vGEOYNGi_c"))
            ->addVideo((new Video())->setUrl("https://www.youtube.com/watch?v=4vGEOYNGi_c"))
            ->addVideo((new Video())->setUrl("https://www.youtube.com/watch?v=4vGEOYNGi_c"))
            ->addComment((new Comment())->setCreationDate($faker->dateTimeBetween($figure->getCreationDate()))
                ->setAuthor($users[array_rand($users)])
                ->setContent("Excellent")
            );
        $manager->persist($figure);

        $manager->flush();
    }
}
