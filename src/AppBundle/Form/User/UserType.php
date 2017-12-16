<?php


namespace AppBundle\Form\User;

use AppBundle\Entity\Club;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $options['entity_manager'];
        $isNewUser = $options['is_new_user'];


        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(),
                    new NotBlank()
                    ]
            ])
            ->add('male', ChoiceType::class, [
                'label' => 'Płeć',
                'placeholder' => 'Wybierz płeć',
                'choices'  => [
                    'Mężczyzna' => 1,
                    'Kobieta' => 0]])
            ->add('name', TextType::class, [
                'label' => 'Imię',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('surname', TextType::class,[
                'label' => 'Nazwisko',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('imageFile', FileType::class,
                ['required' => false])
            ->add('club', EntityType::class, [
                'label' => 'Klub (opcjonalnie)',
                'required' => false,
                'class' => 'AppBundle:Club',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }])
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

            if(!$clubId) {
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

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use($isNewUser) {

            $form = $event->getForm();

            if ($isNewUser) {

                $form->add('plain_password',
                    RepeatedType::class,
                    [
                        'type' => PasswordType::class
                    ]
                )
                ->add('terms', CheckboxType::class, array(
                    'constraints'=>new IsTrue(array('message'=>'Aby się zarejestrować musisz zaakceptować regulamin')),
                    'mapped' => false,
                    'label' => ""))
                ;
            }
        });

    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_new_user' => false
        ]);

        $resolver->setRequired('entity_manager');
    }

}
