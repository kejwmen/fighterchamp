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

    /**
     * @Route("/", name="tournament")
     */
    public function tournamentAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tournaments = $em->getRepository('AppBundle:Tournament')
            ->findAll();

        $userAdminTournaments = null;

        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            $user = $this->getUser();

            $userAdminTournaments = $em->getRepository('AppBundle:UserAdminTournament')
                ->findBy((['user' => $user]));
            }

            $adminTournaments = [];

        foreach ($userAdminTournaments as $item){

           $adminTournaments [] = $item->getTournament();

        }

        dump($adminTournaments);

        dump($tournaments);


        return $this->render('tournament/index.html.twig', array(
            'tournaments' => $tournaments,
            'adminTournaments'=> $adminTournaments
        ));

    }

    /**
     * Creates a new tournament entity.
     *
     * @Route("/new", name="tournament_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $userAdmin = $this->getUser();
        $tournament = new Tournament($userAdmin);
        $form = $this->createForm('AppBundle\Form\TournamentType', $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tournament);
            $em->flush($tournament);

            return $this->redirectToRoute('tournament_show', array('id' => $tournament->getId()));
        }

        return $this->render('admin/tournament/new.html.twig', array(
            'tournament' => $tournament,
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/{id}", name="tournament_show")
     */
    public function tournamentRegisterAction(Request $request, Tournament $tournament)
    {

        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:SignUpTournament')
            ->signUpUserOrder($tournament);

        $fights = $em->getRepository('AppBundle:Fight')
            ->fightReadyOrderBy($tournament);

        $registeredUsersQty = count($users);


        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            $user = $this->getUser();

            if ($user->getBirthDay() == null) {
                return $this->redirectToRoute("regi");
            }


            $isUserRegister = $em->getRepository('AppBundle:SignUpTournament')
                ->findOneBy(array('user' => $user->getId()));

            $birthDay = $user->getBirthDay();
            $tournamentDay = $tournament->getDate();


            $date_diff = date_diff($birthDay, $tournamentDay);
            $date_diff = $date_diff->format("%y");

            $age = ($date_diff >= 18) ? "senior" : "junior";
            $male = $user->getMale();
            $sex = ($male) ? "male" : "female";


            $traitChoices = $em->getRepository('AppBundle:Ruleset')
                ->findBy([$sex => true, $age => true]);

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

                return $this->redirectToRoute("tournament");


            }

            $formDelete = $this->createFormBuilder($isUserRegister)
                ->getForm();

            $formDelete->handleRequest($request);

            if ($formDelete->isValid()) {
                $em->remove($isUserRegister);
                $em->flush();
                return $this->redirectToRoute("tournament");
            }


            $isAdmin = $em->getRepository('AppBundle:UserAdminTournament')
                ->findOneBy(['tournament' => $tournament, 'user' => $user]);




            return $this->render('tournament/register.html.twig', array(
                'form' => $form->createView(),
                'formDelete' => $formDelete->createView(),
                'age' => $age,
                'tournament' => $tournament,
                'users' => $users,
                'date_diff' => $date_diff,
                'registeredUsersQt' => $registeredUsersQty,
                'isUserRegister' => $isUserRegister,
                'fights' => $fights,
                'isAdmin' => $isAdmin
            ));

        } else {

            return $this->render('tournament/register.html.twig', array(
                'tournament' => $tournament,
                'users' => $users,
                'registeredUsersQt' => $registeredUsersQty,
            ));
        }


    }

    /**
     * Displays a form to edit an existing tournament entity.
     *
     * @Route("/{id}/edytuj", name="tournament_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Tournament $tournament)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();

            $isAdmin = $em->getRepository('AppBundle:UserAdminTournament')
                ->findOneBy(['tournament' => $tournament, 'user' => $user]);

            if ($isAdmin) {

                $deleteForm = $this->createDeleteForm($tournament);
                $editForm = $this->createForm('AppBundle\Form\TournamentType', $tournament);
                $editForm->handleRequest($request);

                if ($editForm->isSubmitted() && $editForm->isValid()) {
                    $this->getDoctrine()->getManager()->flush();

                    return $this->redirectToRoute('tournament_edit', array('id' => $tournament->getId()));
                }

                dump($user);

                return $this->render('admin/tournament/edit.html.twig', array(
                    'tournament' => $tournament,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
            }

        }else{
            return new AccessDeniedException();
        }
    }


    /**
     * Deletes a tournament entity.
     *
     * @Route("/{id}", name="tournament_delete")
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

    /**
     * @Route("/{id}/zgloszenia", name="ready_List")
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

        return $this->render('tournament/admin/checkList.html.twig', [
            'signUpTournamnet' => $signUpTournamnet,
            'registeredUsersQty' => $registeredUsersQty,
            'signUpTournamnetChecked' => $signUpTournamnetChecked,
            'signUpTournamnetCheckedQt' => $signUpTournamnetCheckedQt
        ]);

    }


    /**
     * @Route("/{id}/parowanie", name="tournament_match")
     */
    public function pairAction(Request $request, Tournament $tournament)
    {
        $em = $this->getDoctrine()->getManager();

        $fights = $em->getRepository('AppBundle:User')
            ->findAllSignUpButNotPairYet($tournament);

        dump($fights);

        $fight = new Fight();
        $fight->getUsers()->add(null);
        $fight->getUsers()->add(null);

        $form = $this->createForm(FightType::class, $fight,['tournament' => $tournament]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fight = $form->getData();

            $numberOfFights = count($this->getDoctrine()
                ->getRepository('AppBundle:Fight')->findAll());
            $fight->setPosition($numberOfFights + 1);

            $fight->setTournament($tournament);

            $em->persist($fight);
            $em->flush();
        }

        $freeUsers = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')->findAllSignUpButNotPairYet($tournament);

        $registeredUsersQty = count($freeUsers);

        return $this->render('admin/user/pair.html.twig', array(
            'form' => $form->createView(),
            'freeUsers' => $freeUsers,
            'registeredUsersQty' => $registeredUsersQty
        ));
    }

    /**
     * @Route("/{id}/toggle-ready", name="toggleReady")
     * @Method("GET")
     */
    public function toggleReady(SignUpTournament $signUpTournament)
    {

        $signUpTournament->toggleReady();
        $em = $this->getDoctrine()->getManager();
        $em->persist($signUpTournament);
        $em->flush();

        $tournament = $signUpTournament->getTournament();

        return $this->redirectToRoute('ready_List',['id'=>$tournament->getId()]);
    }




}