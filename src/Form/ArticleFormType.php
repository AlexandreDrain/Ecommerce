<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('pictures')
            ->add('product', EntityType::class, [
                'label' => 'Liste des jeux',
                'mapped' => true,
                'multiple' => true,
                'class' => Product::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->where('c.isAvailable = :val')
                    ->setParameter('val', true);
                },
                'choice_label' => 'title',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
