<?php

namespace AppBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FighterCoachType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('birthDay', BirthdayType::class,[
        'label' => 'Data Urodzenia',
        'translation_domain' => true,
        'constraints' => [
            new NotBlank()
        ]
    ])
        ->add('phone', TextType::class, ['label' => 'Telefon']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }


    public function getParent()
    {
        return UserType::class;
    }

}
