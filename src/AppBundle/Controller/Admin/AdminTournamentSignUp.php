<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 14.01.17
 * Time: 04:55
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class AdminTournamentSignUp extends Controller
{
    /**
     * @Route("/turniej/{id}/lista", name="admin_tournament_sign_up")
     */
    public function checkUser(Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();

        $signUpTournamnet = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')
            ->findAllSortByReady($tournament);

        $signUpTournamnetChecked = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')
            ->findBy(['ready' => true], ['ready' => 'ASC']);

        $registeredUsersQty = count($signUpTournamnet);
        $signUpTournamnetCheckedQt = count($signUpTournamnetChecked);

        return $this->render('admin/checkList.html.twig', [
            'signUpTournamnet' => $signUpTournamnet,
            'registeredUsersQty' => $registeredUsersQty,
            'signUpTournamnetChecked' => $signUpTournamnetChecked,
            'signUpTournamnetCheckedQt' => $signUpTournamnetCheckedQt
        ]);
    }
}