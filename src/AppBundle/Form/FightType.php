<?php

namespace AppBundle\Form;

use AppBundle\Entity\Fight;
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
        $builder
            ->add('users', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => array(
                    'class' => 'AppBundle:User',
                    //'choice_label' =>'name',
                    'query_builder' => function(UserRepository $er) {
                        return $er->findAllSignUpButNotPairYet();
                    })
            ])

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
