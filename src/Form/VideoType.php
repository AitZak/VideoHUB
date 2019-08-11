<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Video;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('createdAt', DateTimeType::class,[
                'required'=>true,
                ])
            ->add('published', CheckboxType::class,[
                'required'=>false,
            ])
            ->add('url', UrlType::class,[
                'required'=>true,
            ])
            ->add('description',TextType::class,[
                'required'=> false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'required' => false,
                'choice_label' => 'title'
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
