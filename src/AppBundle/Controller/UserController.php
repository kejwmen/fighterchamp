<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 2016-06-11
 * Time: 12:28
 */

namespace AppBundle\Controller;



use AppBundle\Entity\User;
use AppBundle\Form\EditUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;



/**
 * @Route("/zawodnicy")
 */
class UserController extends Controller
{

    /**
     * @Route("/", name="fighter_all_show")
     */
    public  function showFighters()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')
            ->findBy([],['surname' => 'ASC']);

        return $this->render('fighter/list.html.twig', [
           'users' => $users,
        ]);
    }

//    /**
//     * @Route("/json", name="fighters_json")
//     */
//    public function FightersJson()
//    {
//        $em = $this->getDoctrine()->getManager();
//        $club = $em->getRepository('AppBundle:User')
//            ->findAll();
//
//        $serializer = $this->get('serializer_user');
//        $club = $serializer->serialize($club, 'json');
//
//        return new Response($club, 200, ['Content-Type' => 'application/json']);
//    }


    /**
     * @param User $user
     * @return Response
     *
     * @Route("/{id}", name="user")
     */
    public function showAction(User $user)
    {

        $em = $this->get('doctrine.orm.default_entity_manager');

        $fights = $em->getRepository('AppBundle:Fight')
            ->findAllFightsForUser($user);


        return $this->render('fighter/show.html.twig', [
            'user' => $user,
            'fights' => $fights
        ]);


    }

}