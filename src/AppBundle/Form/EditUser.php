<?php


namespace AppBundle\Form;

use AppBundle\Entity\Club;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EditUser extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $options['entity_manager'];

        $builder
            ->add('email', EmailType::class)
            ->add('male', ChoiceType::class, [
                'label' => 'Płeć',
                'placeholder' => 'Wybierz płeć',
                'choices'  => [
                    'Mężczyzna' => 1,
                    'Kobieta' => 0]])
            ->add('name', TextType::class, ['label' => 'Imię'])
            ->add('surname', TextType::class,['label' => 'Nazwisko'])
            ->add('birthDay', BirthdayType::class,[
                'label' => 'Data Urodzenia',
                'translation_domain' => true
            ])
            ->add('phone', TextType::class, ['label' => 'Telefon'])
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
            ->add('motherName', TextType::class, ['label' => 'Imię Matki'])
            ->add('fatherName', TextType::class, ['label' => 'Imię Ojca'])
            ->add('pesel', TextType::class, ['label' => 'Pesel'])
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
    }





    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);

        $resolver->setRequired('entity_manager');
    }

}
