<?php

namespace App\Form;

use App\Entity\Person;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LikeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Person', EntityType::class, [
                'class' => Person::class,
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('Product', EntityType::class, [
                'class' => Product::class,
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('oldProduct', HiddenType::class)
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
