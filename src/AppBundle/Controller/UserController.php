<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 2016-06-11
 * Time: 12:28
 */

namespace AppBundle\Controller;



use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use AppBundle\Form\EditUser;
use Doctrine\ORM\EntityManager;
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
    public  function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')
            ->findBy([],['surname' => 'ASC']);

        $usersAndFights = $this->getUsersWithStats($users, $em);


        return $this->render('fighter/list.html.twig', [
           'usersAndFights' => $usersAndFights ,
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
     * @Route("/{id}", name="user")
     */
    public function showAction(User $user)
    {

       foreach ($user->getFights() as $fight){
           dump($fight);
       }

        $em = $this->getDoctrine()->getManager();
//        $fights = $em->getRepository('AppBundle:Fight')
//            ->findBy(['users' => $user]);

        $fights = $em->getRepository('AppBundle:Fight')
            ->findAllVisibleForUser($user);


        dump($fights);

        return $this->render('fighter/show.html.twig', [
            'user' => $user,

        ]);
    }

    /**
     * @param $users
     * @param $em
     * @return array
     */
    public function getUsersWithStats($users, EntityManager $em): array
    {
        $usersAndFights = [];

        foreach ($users as $user) {
//            $fights = $em->getRepository('AppBundle:Fight')->findAllFightsForUser($user);

//            $fights = $em->getRepository('AppBundle:Fight')->findBy(['usuer'])

            $stats = ['w' => 0, 'd' => 0, 'l' => 0];

            /**
             * @var $fight Fight
             */
            foreach ($fights as $fight) {

                if ($fight->getWinner()) {

                    if ($user->getId() === $fight->getWinner()->getId()) {
                        $stats['w'] += 1;
                    } else {
                        $stats['l'] += 1;
                    }
                } elseif ($fight->getDraw()) {
                    $stats ['d'] += 1;
                }
            }

            $usersAndFights [] = [$user, $stats];
        }
        return $usersAndFights;
    }

}