<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 05.11.16
 * Time: 10:37
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
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

        $user1 = $em->getRepository('AppBundle:User')
            ->findOneBy(['id' => 1]);

        $tour = new Tournament($user1);
        $tour->setName('Zapis');
        $tour->setCapacity(11);

        $em->persist($tour);

        $em->flush();




        return $this->render(':testowe:testowe.html.twig', [

        ]);


    }

}