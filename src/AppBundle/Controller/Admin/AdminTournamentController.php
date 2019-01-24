<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Tournament;
use AppBundle\Form\TournamentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin")
 */
class AdminTournamentController extends Controller
{
    /**
     * @Route("/turnieje/dodaj", name="admin_tournament_create")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $userAdmin = $this->getUser();
        $tournament = new Tournament();
        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($tournament);
            $em->flush();

            return $this->redirectToRoute('tournament_show', ['id' => $tournament->getId()]);
        }

        return $this->render('admin/tournament/create.html.twig', [
            'tournament' => $tournament,
            'form' => $form->createView(),
        ]);
    }
}