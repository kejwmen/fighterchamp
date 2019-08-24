<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Fight;
use AppBundle\Entity\Info;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Form\SignUpTournamentType;
use AppBundle\Entity\SignUpTournament;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/turnieje")
 */
class TournamentController extends Controller
{
    /**
     * @Route("", name="tournament_list")
     */
    public function listAction(EntityManagerInterface $em): Response
    {
        $tournaments = $em->getRepository(Tournament::class)
            ->findBy([], ['id' => 'DESC']);


        return $this->render('tournament/list.twig',
            [
                'tournaments' => $tournaments,
            ]);
    }

    /**
     * @Route("/{id}", name="tournament_show")
     */
    public function showAction(Tournament $tournament)
    {
        return $this->render('tournament/show.twig',
            [
                'tournament' => $tournament,
            ]);
    }

    /**
     * @Route("/{id}/regulamin", name="tournament_rules")
     */
    public function rulesAction(Tournament $tournament)
    {
        return $this->render("tournament/rules.html.twig",
            [
                'tournament' => $tournament,
            ]);
    }

    /**
     * @Route("/{id}/kontakt", name="tournament_contact")
     */
    public function contactAction(Tournament $tournament)
    {
        return $this->render('tournament/contact.html.twig',
            [
            'tournament' => $tournament,
        ]);
    }

//    /**
//     * Displays a form to edit an existing tournament entity.
//     *
//     * @Route("/{id}/edytuj", name="tournament_edit")
//     * @Method({"GET", "POST"})
//     */
//    public function TournamentEditAction(Request $request, Tournament $tournament)
//    {
//        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
//
//            $user = $this->getUser();
//            $em = $this->getDoctrine()->getManager();
//
//            $isAdmin = $em->getRepository('AppBundle:UserAdminTournament')
//                ->findOneBy(['tournament' => $tournament, 'user' => $user]);
//
//            if ($isAdmin) {
//
//                $deleteForm = $this->createDeleteForm($tournament);
//                $editForm = $this->createForm('AppBundle\Form\TournamentType', $tournament);
//                $editForm->handleRequest($request);
//
//                if ($editForm->isSubmitted() && $editForm->isValid()) {
//                    $this->getDoctrine()->getManager()->flush();
//
//                    return $this->redirectToRoute('tournament_edit', array('id' => $tournament->getId()));
//                }
//
//
//                return $this->render('tournament/edit.html.twig', array(
//                    'tournament' => $tournament,
//                    'edit_form' => $editForm->createView(),
//                    'delete_form' => $deleteForm->createView(),
//                    'isAdmin' => $isAdmin
//                ));
//            }
//
//        }else{
//            return new AccessDeniedException();
//        }
//    }
//
//
//    /**
//     * Deletes a tournament entity.
//     *
//     * @Route("/{id}", name="tournament_delete")
//     * @Method("DELETE")
//     */
//    public function deleteAction(Request $request, Tournament $tournament)
//    {
//        $form = $this->createDeleteForm($tournament);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($tournament);
//            $em->flush($tournament);
//        }
//
//        return $this->redirectToRoute('tournament_list');
//    }
//
//    /**
//     * Creates a form to delete a tournament entity.
//     *
//     * @param Tournament $tournament The tournament entity
//     *
//     * @return \Symfony\Component\Form\Form The form
//     */
//    private function createDeleteForm(Tournament $tournament)
//    {
//        return $this->createFormBuilder()
//            ->setAction($this->generateUrl('tournament_delete', array('id' => $tournament->getId())))
//            ->setMethod('DELETE')
//            ->getForm()
//            ;
//    }


}