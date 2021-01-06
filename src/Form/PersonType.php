<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login')
            ->add('lName')
            ->add('fName')
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'Aktywny' => Person::STATE_AKTYWNY,
                    'Banned' => Person::STATE_BANNED,
                    'Usunięty' => Person::STATE_USUNIETY
                ],
                'label' => 'State'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
