<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 2016-06-11
 * Time: 12:28
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\User\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


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
 //               'users' => $users,
            ]);
    }

    /**
     * @Route("/{id}", name="user_show", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        return $this->render('user/fighter/show.html.twig',
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

        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user,
            [
                'action' => $this->generateUrl('user_update'),
                'method' => 'POST',
                'entity_manager' => $this->get('doctrine.orm.entity_manager'),
                'is_new_user' => false
            ]);

        return $this->render('user/fighter/edit.html.twig',
            [
                'user' => $user,
                'form' => $form->createView()
            ]
        );
    }


    /**
     * @Route("/rejestracja", name="user_create_view")
     * @Method("GET")
     */
    public function newAction()
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user,
            [
                'action' => $this->generateUrl('user_create'),
                'method' => 'POST',
                'entity_manager' => $this->get('doctrine.orm.entity_manager'),
                'is_new_user' => true
            ]);

        return $this->render('security/register.html.twig',
            [
                'entity' => $user,
                'form' => $form->createView()
            ]
        );
    }


}