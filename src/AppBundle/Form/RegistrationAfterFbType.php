<?php

namespace AppBundle\Form;

use AppBundle\Entity\UserModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;


class RegistrationAfterFbType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('birthDay', BirthdayType::class)
            ->add('phone', TextType::class)
            ->add('club', TextType::class)
            ->add('terms', CheckboxType::class, array(
                'constraints'=>new IsTrue(array('message'=>'Aby się zarejestrować musisz zaakceptować regulamin')),
                'mapped' => false,
                'label' => ""))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserModel::class
        ]);
    }

}
