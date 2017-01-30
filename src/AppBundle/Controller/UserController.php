<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 2016-06-11
 * Time: 12:28
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Brawl;
use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use AppBundle\Entity\Fighter;
use AppBundle\Entity\Tournament;
use AppBundle\Form\EditUser;
use AppBundle\Form\RegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends Controller
{

    /**
     * @Route("/zawodnik", name="fighter_all_show")
     */
    public  function showFighters()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')
            ->findBy(array(),['club' => 'ASC']);

        return $this->render('fighter/list.html.twig', [
           'users' => $users,
        ]);
    }

    /**
     * @param User $user
     * @return Response
     *
     * @Route("/zawodnik/{id}", name="user")
     */
    public function showAction(User $user)
    {
        $fights = $user->getFights();
        

        return $this->render('fighter/show.html.twig', [
            'user' => $user,
            'fights' => $fights
        ]);


    }

    /**
     * @Route("/mojprofil/", name="my_profile")
     */
    public function showMyProfile(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute("login");
        }

        $user_id = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(['id' => $user_id]);

        $fights = $user->getFights();

        $form = $this->createForm(EditUser::class, $user,
            [
                'entity_manager' => $this->get('doctrine.orm.entity_manager')
            ]
        );


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }


        return $this->render(
            'fighter/edit.html.twig',
            [
                'user' => $user,
                'fights' => $fights,
                'form' => $form->createView()
            ]
        );

    }

    /**
     * @Route("/mojprofil/setnullonimage", name="setNullOnImage")
     */
        public function setNullOnImageFile()
        {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser()->removeFile();
            $em->flush($user);

            return new Response(200);
        }

}