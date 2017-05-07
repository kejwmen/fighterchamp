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
use AppBundle\Form\EditUser;
use AppBundle\Form\LoginForm;
use AppBundle\Form\PasswordResetType;
use AppBundle\Form\RegistrationAfterFbType;
use AppBundle\Form\RegistrationFacebookForm;
use AppBundle\Form\RegistrationFacebookType;
use AppBundle\Form\RegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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

        $form = $this->createForm(RegistrationType::class, new User(),
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
            [
                'form' => $form->createView(),
            ]
        );

    }

    /**
     * @Route("/rejestracja-facebook", name="register_fb")
     */
    public function registerFBAction(Request $request)
    {

        $session = $this->get('session');

        $facebookId = $session->get('facebookId');

        if(!$facebookId){
            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['facebookId' => $facebookId]);

        if($user){
            $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $user->setFacebookId($session->get('facebookId'));
        $user->setName($session->get('name'));
        $user->setSurname($session->get('surname'));
        $user->setMale($session->get('male'));
        $imageName = $session->get('imageName');
        $user->setEmail($session->get('email'));


        $form = $this->createForm(RegistrationFacebookType::class, $user,
         [
             'entity_manager' => $this->get('doctrine.orm.entity_manager')
         ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            function download_image1($image_url, $image_file){
                $fp = fopen ($image_file, 'w+');              // open file handle

                $ch = curl_init($image_url);
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
                curl_setopt($ch, CURLOPT_FILE, $fp);          // output to file
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1000);      // some large value to allow curl to run for a long time
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                // curl_setopt($ch, CURLOPT_VERBOSE, true);   // Enable this line to see debug prints
                curl_exec($ch);

                curl_close($ch);                              // closing curl handle
                fclose($fp);                                  // closing file handle
            }

            if($imageName){

                $file_name = 'fb_temp';

                download_image1($imageName,$file_name);

                $file = new File($file_name,true);
                $ext = $file->getExtension();

                $image_file = new UploadedFile($file_name.$ext, $file_name.$ext, null, null, null, true);

                $user->setImageFile($image_file);
            }




            $em = $this->getDoctrine()->getManager();
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


            return $this->redirectToRoute('user', ['id' => $user->getId()]);
        }

        return $this->render('security/register_facebook.html.twig', [
            'form' => $form->createView(),
            'imageName' => $imageName,
            'user' => $user
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

    /**
     * @Route("/mojprofil", name="my_profile")
     */
    public function showMyProfile(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute("login");
        }

        $user_id = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(['id' => $user_id]);

        $fights = $em->getRepository('AppBundle:Fight')
            ->findAllFightsForUser($user);

        $form = $this->createForm(EditUser::class, $user,
            [
                'entity_manager' => $em
            ]
        );


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }


        return $this->render(
            'fighter/edit.html.twig',
            [
                'user' => $user,
                'fights' => $fights,
                'form' => $form->createView()
            ]
        );

    }

    /**
     * @Route("/setnullonimage", name="setNullOnImage", options={"expose"=true})
     */
    public function setNullOnImageFile()
    {
        $session = new Session();
        $session->set('imageName', null);

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $em = $this->getDoctrine()->getManager();
            $this->getUser()->removeFile();
            $em->flush();

        }
        return new Response(200);
    }


}