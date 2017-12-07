<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 14.01.17
 * Time: 04:55
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */
class AdminTournamentSignUp extends Controller
{
    /**
     * @Route("/turniej/{id}/lista", name="admin_tournament_sign_up")
     */
    public function signUp(Tournament $tournament)
    {
        $signUpsTournament = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')
            ->findAllForTournament($tournament);

        $signUpsPaid = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')
            ->findAllSignUpsPaid($tournament);

        $signUpsPaidBuTDeleted = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')
            ->findAllSignUpsPaidButDeleted($tournament);

//        $fightsWhereFightersAreNotWeighted = $this->getDoctrine()
//            ->getRepository('AppBundle:Fight')
//            ->findAllTournamentFightsWhereFightersAreNotWeighted($tournament);

        $howManyWeighted = 0;
        foreach($signUpsTournament as $signUp){
            if($signUp->getWeighted() != null)
            {
                $howManyWeighted++;
            }
        }

        $weights = $this->getDoctrine()
            ->getRepository('AppBundle:Ruleset')
            ->getWeight();

        return $this->render('admin/sign_up.html.twig', [
            'signUpsTournament' => $signUpsTournament,
            'signUpsPaid' => $signUpsPaid,
            'signUpsPaidBuTDeleted' => $signUpsPaidBuTDeleted,
            'weights' => $weights,
            'howManyWeighted' => $howManyWeighted,
//            'fightsWhereFightersAreNotWeighted' => $fightsWhereFightersAreNotWeighted
        ]);
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
     * @Route("/set-is-paid", name="set_is_paid")
     */
    public function isPaid(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $signUpId = $request->request->get('signUpId');
        $isPaid =  $request->request->get('isPaid');

        $signUp = $em->getRepository('AppBundle:SignUpTournament')
            ->find($signUpId);

        $signUp->setIsPaid($isPaid);

        $em->flush();

        return new Response(200);
    }

    /**
     * @Route("/sign-up-delete-by-admin/{id}", name="admin_tournament_toggle_delete_by_admin")
     */
    public function toggleDeleteByAdminAction(SignUpTournament $signUpTournament)
    {
        $em = $this->getDoctrine()->getManager();

        $signUpTournament->setDeleteByAdmin($signUpTournament->getDeletedAtByAdmin() ? null : new DateTime('now'));

        $em->flush();

        return $this->redirectToRoute('admin_tournament_pair',['id' => 4]);
    }



}