<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Tournament controller.
 *
 * @Route("admin/turniej")
 */
class TournamentController extends Controller
{
    /**
     * Lists all tournament entities.
     *
     * @Route("/", name="admin_tournament_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tournaments = $em->getRepository('AppBundle:Tournament')->findAll();

        return $this->render('admin/tournament/index.html.twig', array(
            'tournaments' => $tournaments,
        ));
    }

    /**
     * Creates a new tournament entity.
     *
     * @Route("/new", name="admin_tournament_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $tournament = new Tournament();
        $form = $this->createForm('AppBundle\Form\TournamentType', $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tournament);
            $em->flush($tournament);

            return $this->redirectToRoute('admin_tournament_show', array('id' => $tournament->getId()));
        }

        return $this->render('admin/tournament/new.html.twig', array(
            'tournament' => $tournament,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tournament entity.
     *
     * @Route("/{id}", name="admin_tournament_show")
     * @Method("GET")
     */
    public function showAction(Tournament $tournament)
    {
        $deleteForm = $this->createDeleteForm($tournament);

        return $this->render('admin/tournament/show.html.twig', array(
            'tournament' => $tournament,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tournament entity.
     *
     * @Route("/{id}/edit", name="admin_tournament_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Tournament $tournament)
    {
        $deleteForm = $this->createDeleteForm($tournament);
        $editForm = $this->createForm('AppBundle\Form\TournamentType', $tournament);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_tournament_edit', array('id' => $tournament->getId()));
        }

        return $this->render('admin/tournament/edit.html.twig', array(
            'tournament' => $tournament,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tournament entity.
     *
     * @Route("/{id}", name="admin_tournament_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Tournament $tournament)
    {
        $form = $this->createDeleteForm($tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tournament);
            $em->flush($tournament);
        }

        return $this->redirectToRoute('admin_tournament_index');
    }

    /**
     * Creates a form to delete a tournament entity.
     *
     * @param Tournament $tournament The tournament entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tournament $tournament)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_tournament_delete', array('id' => $tournament->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
