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
        $figure->setName("Japan")
            ->setDescription("Saisie de l'avant de la planche, avec la main avant, du côté de la carre frontside")
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
            )
            ->addComment((new Comment())->setCreationDate($faker->dateTimeBetween($figure->getCreationDate()))
                ->setAuthor($users[array_rand($users)])
                ->setContent("Hallucinant")
            )
            ->addComment((new Comment())->setCreationDate($faker->dateTimeBetween($figure->getCreationDate()))
                ->setAuthor($users[array_rand($users)])
                ->setContent("J'ai enfin réussi")
            )
            ->addComment((new Comment())->setCreationDate($faker->dateTimeBetween($figure->getCreationDate()))
                ->setAuthor($users[array_rand($users)])
                ->setContent("Magique")
            );

        $manager->persist($figure);
        $figure = new Figure();
        $figure->setName("540")
            ->setDescription("cinq quatre pour un tour et demi")
            ->setCreationDate($faker->dateTimeBetween("-30 days"))
            ->setCategory($categories["Les rotations"])
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

        $figure = new Figure();
        $figure->setName("900")
            ->setDescription("pour deux tours et demi")
            ->setCreationDate($faker->dateTimeBetween("-30 days"))
            ->setCategory($categories["Les rotations"])
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
        $figure = new Figure();
        $figure->setName("Front flip")
            ->setDescription("Comme un flip, mais en front")
            ->setCreationDate($faker->dateTimeBetween("-30 days"))
            ->setCategory($categories["Les flips"])
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
        $figure = new Figure();
        $figure->setName("Les rotations désaxées")
            ->setDescription("Une rotation désaxée est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier qui désaxe la rotation. Il existe différents types de rotations désaxées (corkscrew ou cork, rodeo, misty, etc.) en fonction de la manière dont est lancé le buste. Certaines de ces rotations, bien qu'initialement horizontales, font passer la tête en bas.")
            ->setCreationDate($faker->dateTimeBetween("-30 days"))
            ->setCategory($categories["Les rotations désaxées"])
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
        $figure = new Figure();
        $figure->setName("Nose slide")
            ->setDescription("Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé.")
            ->setCreationDate($faker->dateTimeBetween("-30 days"))
            ->setCategory($categories["Les slides"])
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
        $figure = new Figure();
        $figure->setName("One foot joe")
            ->setDescription("?")
            ->setCreationDate($faker->dateTimeBetween("-30 days"))
            ->setCategory($categories["Les one foot tricks"])
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
        $figure = new Figure();
        $figure->setName("Backside Air")
            ->setDescription("Dans l'air, mais à l'envers")
            ->setCreationDate($faker->dateTimeBetween("-30 days"))
            ->setCategory($categories["Old school"])
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
