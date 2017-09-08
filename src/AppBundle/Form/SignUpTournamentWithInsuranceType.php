<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\User;




class SignUpTournamentWithInsuranceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $trait = $options['trait_choices'];

        $builder
            ->add('formula', ChoiceType::class, [
                'choices'  => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C'
                ]])
            ->add('trainingTime', IntegerType::class)
            ->add('weight', ChoiceType::class, [
                'choices'  => $trait
                ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\SignUpTournament',
            'trait_choices' => null,
            'user_id' => null,
            'csrf_protection'   => false,
        ));
    }
}
