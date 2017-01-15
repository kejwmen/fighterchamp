<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Form\EditUser;
use AppBundle\Form\FightType;
use AppBundle\Form\PairType;
use AppBundle\Form\RegistrationType;
use AppBundle\Form\SignUpTournamentType;
use AppBundle\Entity\SignUpTournament;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin")
 */
class UserAdminController extends Controller
{


    /**
     * @Route("/zawodnik", name="admin_user_list")
     */
    public function indexAction()
    {

        $users = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findBy(array(), array('surname' => 'ASC'));

        return $this->render('admin/user/list.html.twig', array(
            'genuses' => $users
        ));
    }

    /**
     * @Route("/zawodnik/nowy", name="admin_user_new")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $genus = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($genus);
            $em->flush();


            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/new.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/zawodnik/{id}/edit", name="admin_user_edit")
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(EditUser::class, $user);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }



//    /**
//     * @Route("/{id}/toggle-ready", name="admin_toggleReady")
//     * @Method("GET")
//     */
//    public function toggleReady(SignUpTournament $signUpTournament)
//    {
//
//        $signUpTournament->toggleReady();
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($signUpTournament);
//        $em->flush();
//
//        return $this->redirectToRoute('admin_ready_List');
//    }


    /**
     * @Route("/{id}/edit-complete", name="editSignUpComplete")
     */
    public function editSignUpTournament($id)
    {
        $em = $this->getDoctrine()->getManager();

        $signUpTournament = $this->getDoctrine()
            ->getRepository('AppBundle:SignUpTournament')->find($id);

        $editForm = $this->createForm(new SignUpTournamentType(), $signUpTournament);
    }


}