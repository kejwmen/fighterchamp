<?php

namespace AppBundle\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/api")
 */
class ClubController extends Controller
{
    /**
     * @Route("/kluby")
     */
    public function listAction(EntityManagerInterface $em)
    {
        $clubs = $em->getRepository('AppBundle:Club')
            ->findAllOrderByNumberOfUsers();

        $serializer = $this->get('serializer_club');

        $clubs = $serializer->serialize(['data' => $clubs], 'json');

        return new Response($clubs, 200, ['Content-Type' => 'application/json']);
    }
}
