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
use AppBundle\Entity\UserFight;
use AppBundle\Form\FightType;
use Doctrine\Common\Collections\ArrayCollection;
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

    public function toggleCorners(UserFight $userOneFight, UserFight $userTwoFight): void
    {
        $one = $userOneFight->isRedCorner();
        $two = $userTwoFight->isRedCorner();

        $this->convertNullToFalse($one);
        $this->convertNullToFalse($two);

        if($one === $two){
            $one = true;
            $two = false;
        }

        $one = ($one === true) ? false : true;
        $two = ($two === false) ? true : false;

        $userOneFight->setIsRedCorner($one);
        $userTwoFight->setIsRedCorner($two);
    }

    public function convertNullToFalse(&$arg)
    {
        $arg = $arg ?? false;
    }


    /**
     * @Route("/toggle-corner/walki/{id}", name="toggle_corners")
     */
    public function toggleCornersAction(Fight $fight)
    {
        $em = $this->getDoctrine()->getManager();

        $usersFight = $fight->getUsers();

        $this->toggleCorners($usersFight[0], $usersFight[1]);

        $em->flush();

        return $this->redirectToRoute('admin_tournament_fights', ['id' => 4]);
    }


    /**
     * @Route("/fights-not-weighted-remove", name="fights_not_weighted_remove")
     */
    public function removeFightsWithNotWeighted()
    {
        $em = $this->getDoctrine()->getManager();
        $tournament = $em->getRepository('AppBundle:Tournament')
            ->find(3);

        $fightsWhereFightersAreNotWeighted = $this->getDoctrine()
            ->getRepository('AppBundle:Fight')
            ->findAllTournamentFightsWhereFightersAreNotWeighted($tournament);

        foreach($fightsWhereFightersAreNotWeighted as $fight){
            $em->remove($fight);
            $em->flush();
        }

        $fights = $em->getRepository('AppBundle:Fight')
            ->findAllFightsForTournamentAdmin($tournament);
        $this->refreshFightPosition($fights);


        return $this->redirectToRoute('admin_tournament_sign_up', ['id' => $tournament->getId()]);
    }




    /**
     * @Route("/turniej/{id}/walki", name="admin_tournament_fights")
     */
    public function listAction(Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();
        $fights = $em->getRepository('AppBundle:Fight')
            ->findAllFightsForTournamentAdmin($tournament);


        return $this->render('admin/fight.html.twig', [
            'fights' => $fights,
            'tournament' => $tournament,
        ]);
    }


    /**
     * @Route("/{id}/parowanie", name="admin_tournament_pair")
     */
    public function pairAction(Request $request, Tournament $tournament)
    {
        $freeUsers = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')->findAllSignUpButNotPairYet();

        $users = [];

        foreach($freeUsers as $user)
        {
            $users [] = $this->getDoctrine()->getRepository('AppBundle:SignUpTournament')->find($user['id']);
        }

        return $this->render(':admin:pair.html.twig', array(
            'freeUsers' => $users,
            'tournament' => $tournament
        ));
    }


    /**
     * @Route("/turniej/{id}", name="admin_tournament_create_fight")
     */
    public function createFight(Request $request, Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();

        $data = $request->request->all();

        $signUpRepo = $em->getRepository('AppBundle:SignUpTournament');

        $signUp0 = $signUpRepo->find($data['ids'][0]);
        $signUp1 = $signUpRepo->find($data['ids'][1]);

        $formula = $this->getHighestFormula($signUp0, $signUp1);
        $weight = $this->getHighestWeight($signUp0, $signUp1);

        $fight = new Fight($formula, $weight);

        $fight->addUser($signUp0->getUser());
        $fight->addUser($signUp1->getUser());


        $fight->setTournament($tournament);

        $numberOfFights = count($this->getDoctrine()
            ->getRepository('AppBundle:Fight')->findBy(['tournament' => $tournament]));

        $fight->setPosition($numberOfFights + 1);

        $fight->setTournament($tournament);
        $fight->setDay($tournament->getStart());

        $em->persist($fight);
        $em->flush();


        return new Response();
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


//        $fights = $em->getRepository('AppBundle:Fight')
//            ->findAllFightByDayAdmin($tournament, 'Sobota');
//
//        $this->refreshFightPosition($fights);
//
//
//        $fights = $em->getRepository('AppBundle:Fight')
//            ->findAllFightByDayAdmin($tournament, 'Niedziela');

        $fights = $em->getRepository('AppBundle:Fight')
            ->findAllFightsForTournamentAdmin($tournament);

        $this->refreshFightPosition($fights);


        return $this->redirectToRoute('admin_tournament_fights', ['id' => $tournament->getId()]);
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

        $user ? $fight->setWinner($user) : $fight->resetWinner();

        $draw ? $fight->setDraw(true) : $fight->resetDraw();

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
            ->findAllFightsForTournamentAdmin($tournament);

        $taken_element = array_splice($fights, $position_element_to_take - 1, 1);

        array_splice($fights, $position_to_insert - 1, 0, $taken_element);

        $this->refreshFightPosition($fights);

        return new Response(200);
    }


    /**
     * @Route("/{id}/setwalki", name="allFightsReady")
     */
    public function publishFights(Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository('AppBundle:Fight')->setAllFightsReady($tournament);

        return $this->redirectToRoute('admin_tournament_fights', ['id' => $tournament->getId()]);
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


    /**
     * @Route("/fight/setday", name="setDay")
     * @return Response
     */
    public function setDayAction(Request $request)
    {
        $fightId = $request->request->get('fightId');
        $day = $request->request->get('day');

        $em = $this->getDoctrine()->getManager();

        $tournament = $em->getRepository('AppBundle:Tournament')
            ->findOneBy(['id' => 1]);

        $fight = $em->getRepository('AppBundle:Fight')
            ->findOneBy(['id' => $fightId]);

        $fight->setDay($day);
        $fight->setPosition(100);

        $em->flush();

        $fightsSobota = $em->getRepository('AppBundle:Fight')
            ->findAllFightByDayAdmin($tournament, 'Sobota');

        $i = 1;

        foreach ($fightsSobota as $fight) {

            /**@var Fight $fight */
            $fight->setPosition($i);
            $em->flush();
            $i++;
        }


        $fightsNiedziela = $em->getRepository('AppBundle:Fight')
            ->findAllFightByDayAdmin($tournament, 'Niedziela');

        $i = 1;

        foreach ($fightsNiedziela as $fight) {

            /**@var Fight $fight */
            $fight->setPosition($i);
            $em->flush();
            $i++;
        }

        return new Response(200);
    }


    public function getHighestFormula(SignUpTournament $signUp0, SignUpTournament $signUp1): string
    {
        return ($signUp0->getFormula() <= $signUp1->getFormula()) ? $signUp0->getFormula() : $signUp1->getFormula();
    }

    public function getHighestWeight(SignUpTournament $signUp0, SignUpTournament $signUp1): string
    {
        return ($signUp0->getWeight() >= $signUp1->getWeight()) ? $signUp0->getWeight() : $signUp1->getWeight();
    }


    public function refreshFightPosition($fights): void
    {
        $em = $this->getDoctrine()->getManager();

        $i = 1;
        foreach ($fights as $fight) {

            /**@var Fight $fight */
            $fight->setPosition($i);
            $em->flush();
            $i++;
        }
    }


}