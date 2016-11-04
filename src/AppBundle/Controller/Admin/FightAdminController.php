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
use AppBundle\Form\PairType;
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
     * @Route("/walki", name="admin_fight", options={"expose"=true})
     */
    public function resultAction()
    {
        $em = $this->getDoctrine()->getManager();
        $fights = $em->getRepository('AppBundle:Fight')
            ->fightAllOrderBy();

        $number_of_fights = count($fights);

        return $this->render('admin/fight.html.twig', [
            'fights' => $fights,
            'number_of_fights' => $number_of_fights
        ]);
    }

    /**
     * @Route("/parowanie", name="admin_pair")
     */
    public function pairAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $form = $this->createForm(PairType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fight = $form->getData();

            $numberOfFights = count($this->getDoctrine()
                ->getRepository('AppBundle:Fight')->findAll());

            $fight->setPosition($numberOfFights + 1);

            $em->persist($fight);
            $em->flush();
        }

        $freeUsers = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')->findAllSignUpButNotPairYet();


        $registeredUsersQty = count($freeUsers);

        return $this->render('admin/user/pair.html.twig', array(
            'form' => $form->createView(),
            'freeUsers' => $freeUsers,
            'registeredUsersQty' => $registeredUsersQty
        ));
    }


    /**
     * @Route("/parowanie/{id}", name="find_opponent")
     */
    public function findOpponent(SignUpTournament $signUpTournament)
    {
        $male = $signUpTournament->getUser()->getMale();
        $weight = $signUpTournament->getWeight();
        $formula = $signUpTournament->getFormula();

        $signUpTournament = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')->findOpponent($male, $weight, $formula);

        return new JsonResponse($signUpTournament);

    }

    /**
     * @Route("/fight/{id}/winner/{user_id}", name="setWinner")
     * @Method("GET")
     */
    public function choseWinner(Fight $fight, User $user_id)
    {

        $fight->setWinner($user_id);
        $em = $this->getDoctrine()->getManager();
        $em->persist($fight);
        $em->flush();

        return $this->redirectToRoute('admin_fight');
    }

    /**
     * @Route("/fight/{id}/reset", name="resetWinner")
     */
    public function resetWinner(Fight $fight)
    {
        $fight->resetWinner();
        $em = $this->getDoctrine()->getManager();
        $em->persist($fight);
        $em->flush();

        return $this->redirectToRoute('admin_fight');
    }

    /**
     * @Route("/fight/{id}/remove", name="removeFight")
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

        $em = $this->getDoctrine()->getManager();

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


}