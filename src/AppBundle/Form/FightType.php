<?php

namespace AppBundle\Form;

use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use AppBundle\Repository\SignUpTournamentRepository;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tournament = $options['tournament'];

        $builder
            ->add('day', ChoiceType::class,[
                'choices' => [
                'Niedziela' => 'Niedziela'
            ]
            ])

            ->add('signuptournament', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => 'AppBundle:SignUpTournament',
                    'query_builder' => function(SignUpTournamentRepository $er) use ($tournament) {
                        return $er->findAllSignUpButNotPairYetQB($tournament);
                    }]
            ])


            ->add('formula', ChoiceType::class, [
                'choices' => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                ]
            ]
            )

            ->add('weight', ChoiceType::class, [
                'choices' => [
                    '44' => '44',
                    '46' => '46',
		            '48' => '48',
		            '49' => '49',
		            '50' => '50',
		            '51' => '51',
		            '52' => '52',
                    '54' => '54',
		            '56' => '56',
  		            '57' => '57',
                    '60' => '60',
                    '63' => '63',
			        '64' => '64',
			        '66' => '66',
                    '69' => '69',
                    '70' => '70',
                    '75' => '75',
                    '80' => '80',
                    '80+' => '80+',
                    '81' => '81',
                    '91' => '91',
                    '91+' => '91+'
                ]
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fight::class,
            'tournament' => null
        ]);
    }
}
