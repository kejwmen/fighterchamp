<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInsuranceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('motherName', TextType::class, ['label' => 'Imię Matki'])
        ->add('fatherName', TextType::class, ['label' => 'Imię Ojca'])
        ->add('pesel', TextType::class, ['label' => 'Pesel']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_user_insurance_type';
    }
}
