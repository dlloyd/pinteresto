<?php

namespace App\Form;

use App\Entity\Images;
use App\Entity\ImageCategories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class)
            ->add('category',EntityType::class,[
              'class'=> ImageCategories::class,
              'choice_label'=> 'name',
              'multiple'=> false
            ])
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new Image(['maxSize' => '500k'])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Images::class,
        ]);
    }
}
