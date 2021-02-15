<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Figure;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('category', ChoiceType::class, [
                "choices" => $options['entityManager']->findAll(),
                'choice_value' => 'name',
                'choice_label' => function(?Category $category) {
                    return $category ? strtoupper($category->getName()) : '';
                },
                'choice_attr' => function(?Category $category) {
                    return $category ? ['class' => 'category_'.strtolower($category->getName())] : [];
                }
            ])
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
            'entityManager' => null,
        ]);
    }


    private function fillBusinessUnit(EntityRepository $em)
    {
        $categories = array();
        foreach ($em->findAll() as $category ){
            $categories[] = array("id" => $category->getId(), "name" => $category->getName());
        }
        return $categories;
    }
}
