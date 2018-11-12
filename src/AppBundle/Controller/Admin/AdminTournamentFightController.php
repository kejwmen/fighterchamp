<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Enum\UserFightResult;
use AppBundle\Entity\Fight;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\UserFight;
use AppBundle\Service\FightService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/admin")
 */
class AdminTournamentFightController extends Controller
{

    /**
     * @Route("/toggle-corner/walki/{id}", name="toggle_corners")
     */
    public function toggleCornersAction(Fight $fight, EntityManagerInterface $em, FightService $fightService)
    {
        $fightService->toggleCorners($fight);

        $em->flush();

        return $this->redirectToRoute('admin_tournament_fights', ['id' => 8]);
    }


    /**
     * @Route("/fights-not-weighted-remove", name="fights_not_weighted_remove")
     */
    public function removeFightsWithNotWeighted()
    {
        $em = $this->getDoctrine()->getManager();
        $tournament = $em->getRepository('AppBundle:Tournament')
            ->find(8);

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
    public function listAction(Tournament $tournament, EntityManagerInterface $em)
    {
        $fights = $em->getRepository('AppBundle:Fight')
            ->findAllFightsForTournamentAdmin($tournament);

        return $this->render('admin/fight.html.twig', [
            'fights' => $fights,
            'tournament' => $tournament,
        ]);
    }


    /**
     * @Route("/{id}/parowanie", name="admin_tournament_pair")
     * @Method("GET")
     */
    public function pairAction(Tournament $tournament)
    {
        $freeSignUpIds = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')->findAllSignUpButNotPairYet($tournament->getId());

        $signUps = [];

        foreach($freeSignUpIds as $user)
        {
            $signUps [] = $this->getDoctrine()->getRepository('AppBundle:SignUpTournament')->find($user['id']);
        }

       $normalizeSignUps = $this->get('serializer.my')->normalize($signUps);

        return $this->render(':admin:pair.html.twig', array(
            'freeUsers' => $normalizeSignUps,
        ));
    }


    /**
     * @Route("/walki", name="admin_tournament_create_fight")
     * @Method("POST")
     */
    public function createFight(Request $request, FightService $fightService)
    {
        $data = $request->request->all();

        $fight = $fightService->createFight($data);

        return $fight;
    }


    /**
     * @Route("/walki", name="admin_remove_fight")
     * @Method("DELETE")
     */
    public function deleteFight(Request $request, EntityManagerInterface $entityManager)
    {
        $fightId = $request->request->get('fightId');

        $fightRepository = $entityManager->getRepository(Fight::class);

        $fight = $fightRepository->find($fightId);

        $entityManager->remove($fight);
        $entityManager->flush();

        $fights = $fightRepository->findAllFightsForTournamentAdmin($fight->getTournament());

        $this->refreshFightPosition($fights);


        return new Response(null, 204);
    }


    /**
     * @Route("/fight/set-winner", name="setWinner")
     */
    public function setWinnerAction(Request $request, EntityManagerInterface $em)
    {
        $fightId = $request->request->get('userFightId');
        $result = $request->request->get('result');

        $userFight1 = $em->getRepository(UserFight::class)->find($fightId);
        $userFight2 = $userFight1->getOpponentUserFight();

        switch ($result){
            case 'reset':
                $userFight1->resetResult();
                $userFight2->resetResult();
                break;
            case 'win':
                $userFight1->setResult(UserFightResult::WIN());
                $userFight2->setResult(UserFightResult::LOSE());
                break;
            case 'draw':
                $userFight1->setResult(UserFightResult::DRAW());
                $userFight2->setResult(UserFightResult::DRAW());
                break;

            default:
                throw new \Exception('No fight result');
        }

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
    public function publishFights(Tournament $tournament, EntityManagerInterface $em)
    {
        $em->getRepository('AppBundle:Fight')->setAllFightsReady($tournament);

        return $this->redirectToRoute('admin_tournament_fights', ['id' => $tournament->getId()]);
    }


    /**
     * @Route("/fight/toggleready", name="toggleFightReady")
     */
    public function toggleFightReady(Request $request, EntityManagerInterface $em)
    {

        $fightId = $request->request->get('fightId');

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
    public function setDayAction(Request $request, EntityManagerInterface $em)
    {
        $fightId = $request->request->get('fightId');
        $day = $request->request->get('day');

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



    public function refreshFightPosition($fights): void
    {
        $em = $this->getDoctrine()->getManager();

        $i = 1;
        foreach ($fights as $fight) {

            /**@var Fight $fight */
            $fight->setPosition($i);
            $i++;
        }
        $em->flush();
    }


}