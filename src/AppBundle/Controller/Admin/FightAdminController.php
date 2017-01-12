<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 15.09.16
 * Time: 12:28
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Fight;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\User;
use AppBundle\Form\FightType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/admin")
 */
class FightAdminController extends Controller
{



    /**
     * @Route("/fight/set-winner", name="setWinner")
     */
    public function setWinnerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $fightId = $request->request->get('fightId');
        $userId = $request->request->get('userId');
        $draw = $request->request->get('draw');


        $fight = $em->getRepository('AppBundle:Fight')
            ->findOneBy(['id' => $fightId]);

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(['id' => $userId]);

        $user? $fight->setWinner($user) : $fight->resetWinner();

        $draw? $fight->setDraw(true) : $fight->resetDraw();

        $em->persist($fight);
        $em->flush();

        return $this->redirectToRoute('admin_fight');
    }


    /**
     * @Route("/fight/{id}/remove", name="removeFight")
     * @Method("DELETE")
     */
    public function removeFight(Fight $fight)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($fight);
        $em->flush();

        return $this->redirectToRoute('admin_fight');
    }

    /**
     * @Route("/fight/change-position-fight", name="changePositionFight")
     */
    public function changeOrderFight(Request $request)
    {

        $position_to_insert = $request->request->get('wantedPosition');
        $position_element_to_take = $request->request->get('position');

        $em = $this->getDoctrine()->getManager();
        $fights = $em->getRepository('AppBundle:Fight')
            ->fightAllOrderBy();

        $taken_element = array_splice($fights, $position_element_to_take -1 , 1);

        array_splice($fights, $position_to_insert -1, 0, $taken_element );

        $i = 1;

        $em = $this->getDoctrine()->getManagerager();

        foreach($fights as $fight) {

            /**@var Fight $fight */
            $fight->setPosition($i);
            $em->persist($fight);
            $em->flush();
            $i++;
        }

        return $this->redirectToRoute('admin_fight');
    }

    /**
     * @Route("/setwalki", name="allFightsReady")
     */
    public function publishFights()
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository('AppBundle:Fight')->setAllFightsReady();

        return $this->redirectToRoute('admin_fight');
    }

    /**
     * @Route("/fight/toggleready", name="toggleFightReady")
     */
    public function toggleFightReady(Request $request)
    {

        $fightId = $request->request->get('fightId');
        $em = $this->getDoctrine()->getManager();
        $fight = $em->getRepository('AppBundle:Fight')
            ->findOneBy(['id' => $fightId]);

        $fight->toggleReady();
        $em->persist($fight);
        $em->flush();

        return $this->redirectToRoute('admin_fight');
    }



}