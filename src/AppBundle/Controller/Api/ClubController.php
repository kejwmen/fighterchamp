<?php

namespace AppBundle\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClubController extends Controller
{
    public function listAction(EntityManagerInterface $em)
    {
        $clubs = $em->getRepository('AppBundle:Club')->findAll();

        $result = $this->get('serializer.my')->normalize($clubs, 'json');

        return new JsonResponse(['data' => $result]);
    }
}
