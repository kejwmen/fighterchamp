<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Club;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/kluby")
 */
class ClubController extends Controller
{
    /**
     * @Route("/", name="club_list")
     */
    public function listAction(EntityManagerInterface $em)
    {
        $clubs = $em->getRepository('AppBundle:Club')
            ->findAllOrderByNumberOfUsers();

        return $this->render('club/list.twig',
            [
            'clubs' => $clubs
            ]);
    }

    /**
     * @Route("/{id}", name="club_show")
     */
    public function showAction(Club $club)
    {
        $users = $club->getUsers();

        return $this->render('club/show.twig',
            [
            'users' => $users,
             'club' => $club
            ]);
    }
}
