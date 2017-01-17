<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 10.08.16
 * Time: 19:18
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Form\FightType;
use AppBundle\Form\SignUpTournamentType;
use AppBundle\Entity\SignUpTournament;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



/**
 * @Route("/turniej")
 */
class TournamentController extends Controller
{

//    /**
//     * List all tournaments.
//     *
//     * @Route("/", name="tournament_list")
//     */
//    public function tournamentListAction()
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $tournaments = $em->getRepository('AppBundle:Tournament')
//            ->findAll();
//
//        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
//
//            $user = $this->getUser();
//
//            $userAdminTournaments = $em->getRepository('AppBundle:UserAdminTournament')
//                ->findBy((['user' => $user]));
//
//            $adminTournaments = [];
//
//            foreach ($userAdminTournaments as $item){
//
//                $adminTournaments [] = $item->getTournament();
//            }
//        }
//
//
//        return $this->render('tournament/list.twig', array(
//            'tournaments' => $tournaments,
//            'adminTournaments'=> $adminTournaments ?? null
//        ));
//    }
//
//    /**
//     * Creates a new tournament entity.
//     *
//     * @Route("/new", name="tournament_new")
//     * @Method({"GET", "POST"})
//     */
//    public function TournamentNewAction(Request $request)
//    {
//        $userAdmin = $this->getUser();
//        $tournament = new Tournament($userAdmin);
//        $form = $this->createForm('AppBundle\Form\TournamentType', $tournament);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($tournament);
//            $em->flush($tournament);
//
//            return $this->redirectToRoute('tournament_show', array('id' => $tournament->getId()));
//        }
//
//        return $this->render('admin/tournament/new.html.twig', array(
//            'tournament' => $tournament,
//            'form' => $form->createView(),
//        ));
//    }
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



    /**
     * @Route("/{id}", name="tournament_show")
     */
    public function tournamentShowAction(Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:SignUpTournament')
            ->signUpUserOrder($tournament);

            return $this->render('tournament/show.twig', array(
                'tournament' => $tournament,
                'users' => $users
            ));
    }



    /**
     * @Route("/{id}/zapisy", name="tournament_sign_up")
     */
    public function signUpAction(Tournament $tournament, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $signUpTournament = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')
            ->findAllSortByReady($tournament);

        $signUpTournamentChecked = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')
            ->findBy(['ready' => true, 'tournament' => $tournament], ['ready' => 'ASC']);

        $users = $em->getRepository('AppBundle:SignUpTournament')
            ->signUpUserOrder($tournament);

        $fights = $em->getRepository('AppBundle:Fight')
            ->fightReadyOrderBy($tournament);


        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            $user = $this->getUser();

            if ($user->getBirthDay() == null) {
                return $this->redirectToRoute("regi");
            }


            $isUserRegister = $em->getRepository('AppBundle:SignUpTournament')
                ->findOneBy(['user' => $user->getId()]);

            $birthDay = $user->getBirthDay();
            $tournamentDay = $tournament->getStart();


            $date_diff = date_diff($birthDay, $tournamentDay);
            $date_diff = $date_diff->format("%y");


            if($date_diff <=16){
                $age = 'kadet';
            }elseif ($date_diff <= 18){
                $age = 'junior';
            }else{
                $age = 'senior';
            }

            $male = $user->getMale();
            $sex = ($male) ? "male" : "female";


            $traitChoices = $em->getRepository('AppBundle:Ruleset')
                ->findBy([$sex => true, $age => true],['weight' => 'ASC']);


            $arr = [];

            foreach ($traitChoices as $key => $value) {
                $arr = $arr + array($value->getWeight() => $value->getWeight());
            }

            $signuptournament = new SignUpTournament($user, $tournament);


            $form = $this->createForm(SignUpTournamentType::class, $signuptournament,
                ['trait_choices' => $arr]
            );

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $tournament_register = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($tournament_register);
                $em->flush();

                return $this->redirectToRoute("tournament_sign_up", ['id' => $tournament->getId()]);


            }

            $formDelete = $this->createFormBuilder($isUserRegister)
                ->getForm();

            $formDelete->handleRequest($request);

            if ($formDelete->isValid()) {
                $em->remove($isUserRegister);
                $em->flush();
                return $this->redirectToRoute("tournament_sign_up", ['id' => $tournament->getId()]);
            }

            if ($date_diff <=14) {
                $age = 'młodzik';
            }

            return $this->render('tournament/sign_up.twig', array(
                'form' => $form->createView(),
                'formDelete' => $formDelete->createView(),
                'age' => $age,
                'tournament' => $tournament,
                'users' => $users,
                'date_diff' => $date_diff,
                'isUserRegister' => $isUserRegister,
                'fights' => $fights,
                'signUpTournament' => $signUpTournament,
                'signUpTournamentChecked' => $signUpTournamentChecked,
            ));

        }

        return $this->render('tournament/sign_up.twig', array(
            'tournament' => $tournament,
            'users' => $users,
            'fights' => $fights,
            'signUpTournament' => $signUpTournament,
            'signUpTournamentChecked' => $signUpTournamentChecked,
        ));

    }





}