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
use AppBundle\Entity\UserTournamentAdmin;
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

        /**
         * @var Tournament $tor
         */
        $tor = $em->getRepository('AppBundle:Tournament')
            ->findOneBy(['id' => 10]);

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(['id' => 3]);

        $userTournamentAdmin = new UserTournamentAdmin;

        $userTournamentAdmin->setTournament($tor);
        $userTournamentAdmin->setUser($user);

        $em->persist($userTournamentAdmin);
        $em->flush();

        return $this->render(':testowe:testowe.html.twig', [

        ]);


    }

}