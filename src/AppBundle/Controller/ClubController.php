<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Club;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/kluby")
 */
class ClubController extends Controller
{
    /**
     * @Route("/", name="club_list")
     */
    public function listAction()
    {
        return $this->render('club/list.twig');
    }

    /**
     * @Route("/{id}", name="club_show")
     */
    public function showAction(Club $club)
    {
        return $this->render('club/show.twig',
            [
             'club' => $club
            ]);
    }
}
