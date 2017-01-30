<?php

namespace AppBundle\Form;

use AppBundle\Entity\Club;
use AppBundle\Entity\UserModel;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;


class RegistrationAfterFbType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $options['entity_manager'];

        $builder
            ->add('birthDay', BirthdayType::class)
            ->add('phone', TextType::class)
            ->add('club', EntityType::class, [
                'label' => 'Klub (opcjonalnie)',
                'required' => false,
                'class' => 'AppBundle:Club',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }])
            ->add('terms', CheckboxType::class, array(
                'constraints'=>new IsTrue(array('message'=>'Aby się zarejestrować musisz zaakceptować regulamin')),
                'mapped' => false,
                'label' => ""))
        ;


        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($em) {

            $data = $event->getData();

            if (!$data) {
                return;
            }

            $clubId = $data['club'];


            if ($em->getRepository('AppBundle:Club')->find($clubId)) {
                return;
            }

            $clubName = $clubId;

            $club = new Club();
            $club->setName($clubName);
            $em->persist($club);
            $em->flush();

            $data['club'] = $club->getId();
            $event->setData($data);
        });
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserModel::class
        ]);

        $resolver->setRequired('entity_manager');
    }

}
