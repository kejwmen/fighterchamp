<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 10.08.16
 * Time: 19:18
 */

namespace AppBundle\Controller;

use AppBundle\Form\SignUpTournamentType;
use AppBundle\Entity\SignUpTournament;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class TournamentController extends Controller
{
    /**
     * @Route("turniej", name="tournament")
     */
    public function tournamentRegisterAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:SignUpTournament')
            ->signUpUserOrder();

        $tournament = $em->getRepository('AppBundle:Tournament')
            ->findBy(array('id' => 1));

        $registeredUsersQty = count($users);


        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            $user = $this->getUser();

            if ($user->getBirthDay() == null) {
                return $this->redirectToRoute("regi");
            }


            $isUserRegister = $em->getRepository('AppBundle:SignUpTournament')
                ->findOneBy(array('user' => $user->getId()));

            $birthDay = $user->getBirthDay();
            $tournamentDay = $tournament[0]->getDate();


            $date_diff = date_diff($birthDay, $tournamentDay);
            $date_diff = $date_diff->format("%y");

            $age = ($date_diff >= 18) ? "senior" : "junior";
            $male = $user->getMale();
            $sex = ($male) ? "male" : "female";

            $em = $this->getDoctrine()->getManager();
            $traitChoices = $em->getRepository('AppBundle:Ruleset')
                ->findBy([$sex => true, $age => true]);

            $arr = [];

            foreach ($traitChoices as $key => $value) {
                $arr = $arr + array($value->getWeight() => $value->getWeight());
            }

            $signuptournament = new SignUpTournament($user, $tournament[0]);


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


            return $this->render('tournament/register.html.twig', array(
                'form' => $form->createView(),
                'formDelete' => $formDelete->createView(),
                'age' => $age,
                'tournament' => $tournament[0],
                'users' => $users,
                'date_diff' => $date_diff,
                'registeredUsersQt' => $registeredUsersQty,
                'male' => $male,
                'isUserRegister' => $isUserRegister
            ));

        } else {

            return $this->render('tournament/register.html.twig', array(
                'tournament' => $tournament[0],
                'users' => $users,
                'registeredUsersQt' => $registeredUsersQty,
            ));
        }


    }
}