<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Club;
use AppBundle\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/api")
 */
class FightController extends Controller
{
    /**
     * @Route("/walki")
     */
    public function listAction(EntityManagerInterface $em)
    {
//        $fights = $em->getRepository('AppBundle:Fight')->findAllOrderByNumberOfUsers();
//
//        return new JsonResponse(['data' => $fights]);
    }
}
