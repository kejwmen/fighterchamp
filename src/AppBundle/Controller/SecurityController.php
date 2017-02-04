<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 05.08.16
 * Time: 11:14
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Club;
use AppBundle\Entity\User;
use AppBundle\Entity\UserModel;
use AppBundle\Form\LoginForm;
use AppBundle\Form\PasswordResetType;
use AppBundle\Form\RegistrationAfterFbType;
use AppBundle\Form\RegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */

    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername
        ]);

        return $this->render('security/login.html.twig',
            [
                'form' => $form->createView(),
                'error' => $error,
            ]
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     *
     * @Route("/rejestracja", name="register")
     */
    public function registerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $session = $this->get('session');


        $user = new User();
        $user->setFacebookId($session->get('facebookId'));
        $user->setName($session->get('name'));
        $user->setSurname($session->get('surname'));
        $user->setMale($session->get('male'));
        $imageName = $session->get('imageName');
        $user->setEmail($session->get('email'));

        $session->clear();

        $form = $this->createForm(RegistrationType::class, $user,
            [
            'entity_manager' => $this->get('doctrine.orm.entity_manager')
            ]
        );


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $user = $form->getData();
            $em->persist($user);
            $em->flush();


            $this->addFlash('success', 'Sukces! Twój profil został utworzony! Jesteś zalogowany!');

            $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );
            return $this->redirectToRoute('user', [
                'id' => $user->getId()
            ]);
        }
        return $this->render('security/register.html.twig',
            array(
                'form' => $form->createView(),
                'imageName' => $imageName
            )
        );

    }

    /**
     * @Route("/rejestracja_fb", name="regi")
     */
    public function registerAfterFBAction(Request $request)
    {

        $regiFB = new UserModel();
        $form = $this->createForm(RegistrationAfterFbType::class, $regiFB,
         [
             'entity_manager' => $this->get('doctrine.orm.entity_manager')
         ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $regiFB = $form->getData();
            $user = $this->getUser();
            $user_id = $user->getId();

            /** @var User $user
             * @var UserModel $regiFB
             */
            $user->setPhone($regiFB->getPhone());
            $user->setClub($regiFB->getClub());
            $user->setBirthDay($regiFB->getBirthDay());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Sukces! Twój profil został utworzony! Jesteś zalogowany!');

            return $this->redirectToRoute('user', ['id' => $user_id]);
        }

        return $this->render('security/register_after_fb.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset", name="passwordReset")
     */
    public function passwordReset(Request $request)
    {

        $form = $this->createForm(PasswordResetType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $userEmail = $formData['_username'];

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')
                ->findOneBy(['email' => $userEmail]);

            if (!$user) {
                $this->addFlash('danger_info', 'Użytkownik o podanej nazwie nie istnieje.');
            } else {
                $new_password = time();

                $user->setPlainPassword($new_password);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $appmailer = $this->get('appmailer');

                $text = "Nowe Hasło: " . $new_password;
                $appmailer->sendEmail(
                    $userEmail,
                    'Password Reset',
                    $text);

                $this->addFlash('success_info', 'Sukces. Twoje nowe hasło zostało wysłane na ' . $userEmail);

                return $this->redirectToRoute('login');
            }
        }
        return $this->render('security/password_reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }


}