<?php


namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('plain_password', RepeatedType::class, [
                'type' => PasswordType::class
                ])
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
                ])
            ->add('phone', TextType::class, ['label' => 'Telefon'])
            ->add('imageFile', FileType::class,
                ['required' => false])



            ->add('club', EntityType::class, [
                'label' => 'Klub (opcjonalnie)',
                'required' => false,
                'class' => 'AppBundle:Club'
            ])
            ->add('terms', CheckboxType::class, array(
                'constraints'=>new IsTrue(array('message'=>'Aby się zarejestrować musisz zaakceptować regulamin')),
                'mapped' => false,
                'label' => ""))
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();


            if (!$data) {
                return;
            }

            $categoryId = $data['category'];

            // Do nothing if the category with the given ID exists
            if ($this->em->getRepository(Category::class)->find($categoryId)) {
                return;
            }

            // Create the new category
            $category = new Category();
            $category->setName($categoryId);
            $this->em->persist($category);
            $this->em->flush();

            $data['category'] = $category->getId();
            $event->setData($data);
        });


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['Default', 'Registration']
        ]);
    }


}
