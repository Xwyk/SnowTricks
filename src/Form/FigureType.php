<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Figure;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FigureType extends AbstractType
{
    private $em;

    public function __construct(ObjectManager $em){
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('category', "choice",
                array("label" => "Type",
                    "choices" => $this->fillBusinessUnit(),
                    "attr" => array("class" => "form-control select2"),
                    "empty_value" => 'All Business Units'))
            ->add('videos', CollectionType::class, [
                'by_reference' => false,
                'entry_type' => VideoType::class,
                'entry_options' => ['label' => false],
                'allow_add'=>true,
                'allow_delete' => true,
                'prototype' => true
            ])
            ->add('pictures', CollectionType::class, [
                'by_reference' => false,
                'entry_type' => PictureType::class,
                'entry_options' => ['label' => false],
                'allow_add'=>true,
                'allow_delete' => true,
                'prototype' => true
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }


    private function fillBusinessUnit()
    {
        $results = $this->em->createQueryBuilder('e')
            ->orderBy('e.name', 'ASC');

        $businessUnit = array();
        foreach ($results as $bu) {
            $businessUnit[] = array("id" => $bu->getId(), "name" => $bu->getName()); // and so on..
        }

        return $businessUnit;
    }
}
