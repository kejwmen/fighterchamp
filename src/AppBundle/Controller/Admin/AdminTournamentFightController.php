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
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Form\FightType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/admin")
 */
class AdminTournamentFightController extends Controller
{
    /**
     * @Route("/turniej/{id}/walki", name="admin_tournament_fights")
     */
    public function resultAction(Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();
        $fights = $em->getRepository('AppBundle:Fight')
            ->fightAllOrderBy($tournament);

        $number_of_fights = count($fights);

        return $this->render('admin/fight.html.twig', [
            'fights' => $fights,
            'number_of_fights' => $number_of_fights,
            'tournament' => $tournament
        ]);
    }


    /**
     * @Route("/{id}/parowanie", name="admin_tournament_pair")
     */
    public function pairAction(Request $request, Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();

        $fights = $em->getRepository('AppBundle:User')
            ->findAllSignUpButNotPairYet($tournament);


        $fight = new Fight();
        $fight->getUsers()->add(null);
        $fight->getUsers()->add(null);

        $form = $this->createForm(FightType::class, $fight,['tournament' => $tournament]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fight = $form->getData();

            $numberOfFights = count($this->getDoctrine()
                ->getRepository('AppBundle:Fight')->findAll());
            $fight->setPosition($numberOfFights + 1);

            $fight->setTournament($tournament);

            $em->persist($fight);
            $em->flush();
        }

        $freeUsers = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')->findAllSignUpButNotPairYet($tournament);


        return $this->render('admin/user/pair.twig', array(
            'form' => $form->createView(),
            'freeUsers' => $freeUsers,
            'tournament' => $tournament,
        ));
    }

    /**
     * @Route("/{id}/toggle-ready", name="toggleReady")
     * @Method("GET")
     */
    public function toggleReady(SignUpTournament $signUpTournament)
    {
        $signUpTournament->toggleReady();
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $tournament = $signUpTournament->getTournament();

        return $this->redirectToRoute('admin_tournament_sign_up',['id'=>$tournament->getId()]);
    }


    /**
     * @Route("/{id}/fight/{fight_id}/remove", name="admin_remove_fight")
     * @ParamConverter("fight", options={"id" = "fight_id"})
     */
    public function removeFight(Fight $fight, Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($fight);
        $em->flush();

        return $this->redirectToRoute('admin_tournament_sign_up',['id' => $tournament->getId()]);
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

        return new Response(200);
    }


    /**
     * @Route("/{id}/fight/change-position-fight", name="changePositionFight")
     */
    public function changeOrderFight(Request $request, Tournament $tournament)
    {

        $position_to_insert = $request->request->get('wantedPosition');
        $position_element_to_take = $request->request->get('position');

        $em = $this->getDoctrine()->getManager();
        $fights = $em->getRepository('AppBundle:Fight')
            ->fightAllOrderBy($tournament);

        $taken_element = array_splice($fights, $position_element_to_take -1, 1);

        array_splice($fights, $position_to_insert -1, 0, $taken_element);

        $i = 1;

        foreach($fights as $fight) {

            /**@var Fight $fight */
            $fight->setPosition($i);
            $em->flush();
            $i++;
        }

        return new Response(200);
    }

    /**
     * @Route("/{id}/setwalki", name="allFightsReady")
     */
    public function publishFights(Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository('AppBundle:Fight')->setAllFightsReady($tournament);

        return $this->redirectToRoute('tournament_fights',['id' => $tournament->getId()]);
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
        $em->flush();

        return new Response(200);
    }



}