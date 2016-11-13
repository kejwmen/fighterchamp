<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 05.11.16
 * Time: 10:37
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class TestoweController extends Controller
{
    /**
     * @Route("/testowe", name="testowe")
     */
    public function resultAction()
    {

        $em = $this->getDoctrine()->getManager();

        $user1 = new User();
        $user2 = new User();

        $fight = new Fight();

        $fight->addUser($user1);

        $fight->addUser($user2);

        $em->persist($fight);
        $em->flush();

        return $this->render(':testowe:testowe.html.twig', [

        ]);


    }
}