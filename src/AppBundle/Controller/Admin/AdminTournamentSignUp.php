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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        $em = $this->getDoctrine()->getManager();


        $signUpsTournament = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')
            ->findAllSortByReady($tournament);

//        $signUpsTournamentReady = $this->getDoctrine()
//            ->getRepository('AppBundle:SignUpTournament')
//            ->findBy(['ready' => true], ['ready' => 'ASC']);

        $signUpsPaid = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')
            ->findBy(['isPaid' => true], ['ready' => 'ASC']);


        return $this->render('admin/sign_up.html.twig', [
            'signUpsTournament' => $signUpsTournament,
           // 'signUpsTournamentReady' => $signUpsTournamentReady,
            'signUpsPaid' => $signUpsPaid
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
        $isPaid = $request->request->get('isPaid');

        $signUp = $em->getRepository('AppBundle:SignUpTournament')
            ->findOneBy(['id' => $signUpId]);

        $signUp->setIsPaid($isPaid);

        $em->flush();

        return new Response(200);
    }


}