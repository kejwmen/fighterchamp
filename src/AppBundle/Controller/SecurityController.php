<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 05.08.16
 * Time: 11:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Security\LoginForm;
use AppBundle\Form\Security\PasswordResetType;
use AppBundle\Form\User\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Mailer;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

            if($imageName){

                $file_name = 'fb_temp';

                $this->download_image1($imageName,$file_name);

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

    public function download_image1($image_url, $image_file){
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


             if($user) {
                 $new_password = time();

                 $user->setPlainPassword($new_password);

                 $em = $this->getDoctrine()->getManager();
                 $em->persist($user);
                 $em->flush();

                 $transport = (new Swift_SmtpTransport('smtp.zenbox.pl', 587))
                     ->setUsername('fighterchamp@fighterchamp.pl')
                     ->setPassword('Cortez1634')
                 ;

                 $mailer = new Swift_Mailer($transport);

                 $message = \Swift_Message::newInstance()
                     ->setSubject('Password Reset')
                     ->setFrom('fighterchamp@fighterchamp.pl', 'FighterChamp')
                     ->setTo($userEmail)
                     ->setBody("Nowe Hasło: " . $new_password, 'text/html');

                 $numberOfSuccessfulSent = $mailer->send($message);

                 $this->addFlash('success_info', 'Sukces. Twoje nowe hasło zostało wysłane na ' . $userEmail);

             }else {
                     $this->addFlash('danger_info', 'Użytkownik o podanej nazwie nie istnieje.');
             }

                return $this->redirectToRoute('login');
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