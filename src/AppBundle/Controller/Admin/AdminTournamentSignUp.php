<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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

//        $form = $this->createForm(SignUpTournamentType::class, null,
//            ['trait_choices' => $weights]
//        );

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
     * @Route("/set-is-paid", name="set_is_paid")
     */
    public function isPaid(Request $request, EntityManagerInterface $em)
    {
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
    public function toggleDeleteByAdminAction(SignUpTournament $signUpTournament, EntityManagerInterface $em)
    {
        $signUpTournament->setDeleteByAdmin($signUpTournament->getDeletedAtByAdmin() ? null : new DateTime('now'));

        $em->flush();

        return $this->redirectToRoute('admin_tournament_pair',['id' => 6]); //todo change to 200 and js reload page
    }



}