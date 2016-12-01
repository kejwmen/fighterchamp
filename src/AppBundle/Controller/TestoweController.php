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

        $users = $em->getRepository('AppBundle:User')
            ->findAllSignUpButNotPairYet();


        dump($users);




        return $this->render(':testowe:testowe.html.twig', [

        ]);


    }

    public function more($arr = null, $more = null)
    {

    }
}