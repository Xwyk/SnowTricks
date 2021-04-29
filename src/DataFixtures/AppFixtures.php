<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\Picture;
use App\Entity\User;
use App\Entity\Video;
use App\Service\FileUploader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class AppFixtures extends Fixture
{
    private FileUploader $fileUploader;
    private string $rootPublicDirectory;
    private string $pictures_directory;
    private \Faker\Generator $faker;
    const COMMENTS = [
        'Excellent !',
        'Wow, halluciant',
        'C\'est vraiment le meilleur trick au monde',
        'Mon père s\'est cassé une jambe en le faisant !',
        'Demande de la maîtrise, mais juste parfait',
        'Merci pour le partage',
        'N\'hésitez pas à partager sur tous les réseaux !',
        'Chapeau',
        'Je pensais ça possible uniquement dans les SSX',
        'Vivement que je reçoive mon nouveau snow',
        'Ce site est juste parfait pour tout partager',
        'Un véritable puis de savoir est disponible',
        'Superbes explications',
        'Bravo',
        'Egalement réalisable en reverse !'];
    const CATEGORIES = [
        "Les grabs" => [
            'Japan' => [
                'description' => "Saisie de l'avant de la planche, avec la main avant, du côté de la carre frontside",
                'videos' => [

                ],
                'images' => [

                ]
            ],
            'Seatbelt'=> [
                'description' => "",
                'videos' => [

                ],
                'images' => [

                ]
            ],
        ],
        "Les rotations" => [
            '540' => [
                'description' => "cinq quatre pour un tour et demi"
            ],
            '900'=> [
                'description' => "pour deux tours et demi",
                'videos' => [

                ],
                'images' => [

                ]
            ]
        ],
        "Les flips" =>[
            'Front flip'=> [
                'description' => "Comme un flip, mais en front",
                'videos' => [

                ],
                'images' => [

                ]
            ]
        ],
        "Les rotations désaxées"=>[
            'Rotation désaxée'=> [
                'description' => "Les rotations désaxées",
                'videos' => [

                ],
                'images' => [

                ]
            ]
        ],
        "Les slides"=>[
            'Nose slide'=> [
                'description' => "Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé.",
                'videos' => [

                ],
                'images' => [

                ]
            ]
        ],
        "Les one foot tricks"=>[
            'One foot joe'=> [
                'description' => "?",
                'videos' => [

                ],
                'images' => [

                ]
            ]
        ],
        "Old school"=>[
            'Backside Air'=> [
                'description' => "Dans l'air, mais à l'envers",
                'videos' => [

                ],
                'images' => [

                ]
            ]
        ]
    ];

    public function __construct(FileUploader $fileUploader, string $rootPublicDirectory, string $pictures_directory)
    {
        $this->faker = \Faker\Factory::create("fr_FR");
        $this->fileUploader = $fileUploader;
        $this->rootPublicDirectory = $rootPublicDirectory;
        $this->pictures_directory = $pictures_directory;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        // Create fake users
        $users = array();
        $this->createUsers($users);
        foreach ($users as $user){
            $manager->persist($user);
        }

        // Create fake categories
        $categories = array();
        $this->createCategories($categories);
        foreach ($categories as $category){
            $manager->persist($category);
        }

        $figures = array();
        $this->createFigures($figures, $categories, $users);
        foreach ($figures as $figure){
            $manager->persist($figure);
        }

        $manager->flush();
    }

    private function createUsers(array $users){
        for ($i = 1; $i <= 4; $i++){
            $users['user'.$i] = (new User())->setPseudo('user'.$i)
                ->setPassword((password_hash('user'.$i, PASSWORD_BCRYPT )))
                ->setIsVerified(true)
                ->setMailAddress('user'.$i.'@snowtricks.fr')
                ->setCreationDate($this->faker->dateTimeBetween("-90 days", "-31 days"));
        }
    }

    private function createCategories(array $categories){
        foreach ($this::CATEGORIES as $name => $figures){
            $categories[$name] = (new Category())->setName($name);
        }
    }

    private function createFigures(array $figures, array $categories, array $users){
        foreach ($this::CATEGORIES as $categoryName => $categoryFigures){
            foreach ($categoryFigures as $figureName => $figureContent){
                $figures[$figureName] = (new Figure())->setName($figureName)
                    ->setDescription($figureContent)
                    ->setCreationDate($this->faker->dateTimeBetween("-30 days", "now"))
                    ->setCategory($categories[$categoryName]);
                $this->addPicturesToFigure($figures[$figureName]);
                $this->createCommentsForFigure($figures[$figureName], $users);
            }
        }
    }

    private function createCommentsForFigure(Figure $figure,array $users){
        $figureCommentsTexts = $this::COMMENTS;
        shuffle($figureCommentsTexts);
        foreach ($figureCommentsTexts as $commentText){
            $figure->addComment((new Comment())->setCreationDate($this->faker->dateTimeBetween($figure->getCreationDate(), "now"))
                ->setAuthor($users[array_rand($users)])
                ->setContent($commentText)
            );
        }
    }

    private function addPicturesToFigure(Figure $figure){
        foreach ($this::CATEGORIES[$figure->getCategory()->getName()][$figure->getName()]["images"] as $image){
            $figure->addPicture(
                (new Picture())->setUrl(
                    $this->fileUploader->uploadPicture(
                        new File($this->rootPublicDirectory.$image)
                    )
                )
            );
        }
    }
}
