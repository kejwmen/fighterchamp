<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Club;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClubController extends Controller
{
    public function listAction(EntityManagerInterface $em)
    {
        $clubs = $em->getRepository(Club::class)->findAll();

        return $clubs;
    }
}
