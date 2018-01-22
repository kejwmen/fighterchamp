<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 2016-06-11
 * Time: 12:28
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\User\CoachType;
use AppBundle\Form\User\FighterType;
use AppBundle\Form\User\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/ludzie")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="user_list")
     * @Method("GET")
     */
    public function listAction(EntityManagerInterface $em)
    {
        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/list.html.twig',
            [
                'users' => $users,
            ]);
    }

    /**
     * @Route("/{id}", name="user_show", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        return $this->render($this->getShowViewType($user),
            [
                'user' => $user,
            ]);
    }


    /**
     * @Route("/mojprofil", name="user_edit_view")
     */
    public function updateAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute("login");
        }

        return $this->render('user/edit.html.twig',
            [
                'user' => $this->getUser()
            ]);
    }


    /**
     * @Route("/rejestracja", name="user_create_view")
     * @Method("GET")
     */
    public function newAction(SessionInterface $session)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute('homepage');
        }

        $facebookId = $session->get('facebookId');
        $session->clear();


        return $this->render('security/register.html.twig',[
            'facebookId' => $facebookId
        ]);
    }

    /**
     * @Route("/register-form/{type}", options={"expose"=true}, name="user_register_form_view")
     * @Method("GET")
     */
    public function formAction($type)
    {
        $form = $this->createForm($this->getFormType($type), null, [
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST'
        ]);

        return $this->render($this->getFormTypeView($type),
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/update-form/{type}", options={"expose"=true}, name="user_update_form_view")
     * @Method("GET")
     */
    public function formUpdateAction($type)
    {
        $user = $this->getUser();

        if(!$user) {
            $session = $this->get('session');
            $user = new User();
            $user->setFacebookId($session->get('facebookId'));
            $user->setName($session->get('name'));
            $user->setSurname($session->get('surname'));
            $user->setMale($session->get('male'));
//        $imageName = $session->get('imageName');
            $user->setEmail($session->get('email'));
        }



        $form = $this->createForm($this->getFormType($type), $user, [
            'action' => $this->generateUrl('api_user_update'),
            'method' => 'POST'
        ]);

        return $this->render($this->getFormTypeUpdateView($type),
            [
                'form' => $form->createView()
            ]
        );
    }

    private function getFormTypeUpdateView(string $type): string
    {
        switch ($type) {
            case '1':
                return 'user/fighter/_edit.html.twig';
            case '2':
                return 'user/coach/_edit.html.twig';
            case '3':
                return 'user/fan/_edit.html.twig';
            default:
                return 'Nie ma takiego typu';
        }
    }


    private function getFormTypeView(string $type): string
    {
        switch ($type) {
            case '1':
                return 'user/fighter/_form.html.twig';
            case '2':
                return 'user/coach/_form.html.twig';
            case '3':
                return 'user/fan/_form.html.twig';
            default:
                return 'Nie ma takiego typu';
        }
    }

    private function getFormType(string $type): string
    {
        switch ($type) {
            case '1':
                return FighterType::class;
            case '2':
                return CoachType::class;
            case '3':
                return UserType::class;
            default:
                return 'Nie ma takiego typu';
        }
    }

    private function getShowViewType(User $user)
    {
        switch ($user->getType()) {
            case 1:
                return 'user/fighter/show.html.twig';
            case 2:
                return 'user/coach/show.html.twig';
            case 3:
                return 'user/fan/fan.html.twig';
            default:
                return 'Nie ma takiego typu';
        }
    }


}