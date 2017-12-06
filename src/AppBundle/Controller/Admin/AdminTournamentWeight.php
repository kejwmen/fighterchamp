<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 05.02.17
 * Time: 10:57
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */
class AdminTournamentWeight extends Controller
{
    /**
     * @Route("/set-weighted", name="set_weighted")
     */
    public function setWeighted(Request $request)
    {
        $signUpId = $request->request->get('signUpId');
        $weighted = $request->request->get('weighted');

        $em = $this->getDoctrine()->getManager();

        $signUp = $em->getRepository('AppBundle:SignUpTournament')
            ->find($signUpId);

        $signUp->setWeighted($weighted);

        $tournament = $em->getRepository('AppBundle:Tournament')->find(4);

        if($weighted != $signUp->getWeight()){

            $fights = $em->getRepository('AppBundle:Fight')
                ->findUserFightsInTournament($signUp->getUser(), $tournament );

            if($fights){
                foreach($fights as $fight){

                    $users = $fight->getUsers();

                    $em->remove($fight);


                    $this->addFlash('warning', "Walka $users[0] vs. $users[1] została rozparowana ");
                }
            }
        }

        $em->flush();




        return new Response(200);
    }
}