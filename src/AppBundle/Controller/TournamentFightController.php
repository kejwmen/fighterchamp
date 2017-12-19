<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 15.09.16
 * Time: 12:28
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Fight;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/turnieje")
 */
class TournamentFightController extends Controller
{
    /**
     * @Route("/{id}/walki", name="tournament_fights")
     */
    public function resultAction(Tournament $tournament)
    {
        $fightsInDay = [];

        foreach ($tournament->getFightsReady() as $fight)
        {
            $fightsInDay[$fight->getDay()->format('Y-m-d')][] = $fight;
        }

        return $this->render('tournament/fights.html.twig',
            [
                'fightsInDay' => $fightsInDay,
                'tournament' => $tournament

            ]);
    }
}