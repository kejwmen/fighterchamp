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
     * @Route("", name="club_list")
     */
    public function listAction()
    {
        return $this->render('club/list.twig');
    }

    /**
     * @Route("/{id}", name="club_show", options={"expose"=true})
     */
    public function showAction(Club $club)
    {
        $club = $this->get('serializer.my')->normalize($club);

        return $this->render('club/show.twig',
            [
             'club' => $club
            ]);
    }
}
