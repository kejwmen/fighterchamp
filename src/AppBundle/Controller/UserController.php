<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 2016-06-11
 * Time: 12:28
 */

namespace AppBundle\Controller;



use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use AppBundle\Form\EditUser;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * @Route("/zawodnicy")
 */
class UserController extends Controller
{

    /**
     * @Route("/", name="fighter_all_show")
     */
    public  function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/fighter/list.html.twig', [
           'users' => $users ,
        ]);
    }


    /**
     * @Route("/{id}", name="user")
     */
    public function showAction(User $user)
    {
        return $this->render('user/fighter/show.html.twig', [
            'user' => $user,
        ]);
    }
}