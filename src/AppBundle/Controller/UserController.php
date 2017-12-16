<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 2016-06-11
 * Time: 12:28
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * @Route("/zawodnicy")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="fighter_all_show")
     */
    public function listAction(EntityManagerInterface $em)
    {
        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/fighter/list.html.twig',
            [
                'users' => $users,
            ]);
    }


    /**
     * @Route("/{id}", name="user")
     */
    public function showAction(User $user)
    {
        return $this->render('user/fighter/show.html.twig',
            [
                'user' => $user,
            ]);
    }
}