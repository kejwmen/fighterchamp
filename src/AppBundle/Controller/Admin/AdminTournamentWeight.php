<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 05.02.17
 * Time: 10:57
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Tournament;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin")
 */
class AdminTournamentWeight extends Controller
{
    /**
     * @Route("/turniej/{id}/waga", name="admin_tournament_weight")
     */
    public function weightAction(Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();

        $signUpsTournament = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')
            ->findAllSortByReady($tournament);

        return $this->render('admin/weight.html.twig', [
           
        ]);


    }
}