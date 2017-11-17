<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 7/29/17
 * Time: 2:08 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Fight;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/walki")
 */
class FightController extends Controller
{

    /**
     * @Route("/{id}", name="fight_show")
     */
    public function showAction(Fight $fight)
    {
        return $this->render('fight/show.html.twig',
            [
                'fight' => $fight,
            ]);
    }

    /**
     * @Route("", name="fight_list")
     */
    public function listAction(Request $request)
    {
        $tournamentId = $request->query->get('tournament');

        $em = $this->getDoctrine()->getManager();

        if ($tournamentId) {

            $tournament = $em->getRepository('AppBundle:Tournament')
                ->find(3);

            $fights = $em->getRepository('AppBundle:Fight')
                ->findAllFightsForTournament($tournament);

            $fightsInDay = [];

            foreach ($fights as $fight) {
                $fightsInDay[$fight->getDay()->format('Y-m-d')][] = $fight;
            }

            return $this->render('tournament/fights.html.twig', [
                'fightsInDay' => $fightsInDay

            ]);


        } else {
            $fights = $em->getRepository(Fight::class)->findBy(['ready' => true], ['youtubeId' => 'DESC']);


            return $this->render('fight/list.html.twig',
                [
                    'fights' => $fights,
                ]);
        }
    }

    /**
     * @Route("/{id}/fight/{fight_id}/remove", name="admin_remove_fight")
     * @ParamConverter("fight", options={"id" = "fight_id"})
     */
    public function deleteAction(Fight $fight, Tournament $tournament)
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

//        $this->refreshFightPosition($fights);


        return $this->redirectToRoute('admin_tournament_fights', ['id' => $tournament->getId()]);
    }

    public function updateAction()
    {

    }

    /**
     * @Route("/turniej/{id}", name="admin_tournament_create_fight")
     */
    public function createFight(Request $request, Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();

        $data = $request->request->all();

        $fight = new Fight();

        $signUpRepo = $em->getRepository('AppBundle:SignUpTournament');

        $signUp0 = $signUpRepo->find($data['ids'][0]);
        $signUp1 = $signUpRepo->find($data['ids'][1]);

        $fight->addUser($signUp0->getUser());
        $fight->addUser($signUp1->getUser());

        $formula = $this->getHighestFormula($signUp0, $signUp1);
        $fight->setFormula($formula);

        $weight = $this->getHighestWeight($signUp0, $signUp1);
        $fight->setWeight($weight);

        $fight->setTournament($tournament);

        $numberOfFights = count($this->getDoctrine()
            ->getRepository('AppBundle:Fight')->findBy(['tournament' => $tournament]));

        $fight->setPosition($numberOfFights + 1);

        $fight->setTournament($tournament);
        $fight->setDay($tournament->getStart());

        $em->persist($fight);
        $em->flush();


        return new JsonResponse(null,200);
    }

    public function getHighestFormula(SignUpTournament $signUp0, SignUpTournament $signUp1): string
    {
        return ($signUp0->getFormula() <= $signUp1->getFormula()) ? $signUp0->getFormula() : $signUp1->getFormula();
    }

    public function getHighestWeight(SignUpTournament $signUp0, SignUpTournament $signUp1): string
    {
        return ($signUp0->getWeight() >= $signUp1->getWeight()) ? $signUp0->getWeight() : $signUp1->getWeight();
    }
}