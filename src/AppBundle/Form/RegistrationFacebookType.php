<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFacebookType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('terms', CheckboxType::class, array(
                'constraints'=>new IsTrue(array('message'=>'Aby się zarejestrować musisz zaakceptować regulamin')),
                'mapped' => false,
                'label' => ""))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getParent()
    {
        return EditUser::class;
    }


}
