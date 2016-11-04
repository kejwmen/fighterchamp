<?php

namespace AppBundle\Form;

use AppBundle\Entity\Fight;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PairType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('formula', ChoiceType::class, array(
                'choices' => array(
                    'Boks' => 'Boks',
                    'K1' => 'K1',
                    'Kick Boxing Low-Kick' => 'Kick Boxing Low-Kick',
                    'Kick Boxing Oriental Rules' => 'Kick Boxing Oriental Rules')
            ))
            ->add('weight', ChoiceType::class, array(
                'choices' => array('51.0' => '51.0', '54.0' => '54.0','60' => '60',
                    '57' => '57','63,5' => '63,5','67' => '67','71' => '71','75' => '75'
                ,'75+' => '75+','81' => '81','61+' => '81+','86' => '86','91' => '91'
                ,'91+' => '91+')
            ))
            ->add('UserOne', EntityType::class, array(
                'class' => 'AppBundle:User',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('user')
                        ->leftJoin('user.signUpTournament', 'signUpTournament')
                        ->andWhere('signUpTournament.user is not null')
                        ->leftJoin('user.fights', 'fights' )
                        ->andwhere('fights.userOne is null')
                        ->leftJoin('user.additionalFights', 'fights2' )
                        ->andwhere('fights2.userTwo is null')
                        ->andwhere('signUpTournament.ready = 1')
                        ->orderBy('user.surname');
                        }))
            ->add('UserTwo', EntityType::class, array(
                    'class' => 'AppBundle:User',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('user')
                            ->leftJoin('user.signUpTournament', 'signUpTournament')
                            ->andWhere('signUpTournament.user is not null')
                            ->leftJoin('user.fights', 'fights' )
                            ->andwhere('fights.userOne is null')
                            ->leftJoin('user.additionalFights', 'fights2' )
                            ->andwhere('fights2.userTwo is null')
                            ->andwhere('signUpTournament.ready = 1')
                            ->orderBy('user.surname');
                    }))
            ->add('tournament')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fight::class,
        ]);
    }
}
