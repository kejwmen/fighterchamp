<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 9/1/17
 * Time: 12:10 AM
 */

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/trenerzy")
 */
class CouchController extends Controller
{
    /**
     * @Route("/", name="fighter_all_show")
     */
    public  function showFighters()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')
            ->findBy([],['surname' => 'ASC']);

        return $this->render('fighter/list.html.twig', [
            'users' => $users,
        ]);
    }
}