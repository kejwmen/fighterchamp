<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Fight;
use AppBundle\Entity\SignUpTournament;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SignUpController extends Controller
{
    public function listAction(EntityManagerInterface $em)
    {

    }

    public function showAction(SignUpTournament $signUp)
    {
        $result = $this->get('serializer.my')->serialize($signUp, 'json');

        return new Response($result, 200, ['Content-Type' => 'application/json']);
    }
}
