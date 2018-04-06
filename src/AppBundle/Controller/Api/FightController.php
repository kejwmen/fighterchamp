<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class FightController extends Controller
{
    public function showAction(Fight $fight)
    {
        $result = $this->get('serializer.my')->serialize($fight, 'json');

        return new Response($result, 200, ['Content-Type' => 'application/json']);
    }

    public function listAction($id, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        if((int)$id > 0){
            $tournament = $em->getRepository(Tournament::class)->find($id);

            $fights = $em->getRepository(Fight::class)
                ->findBy(['tournament' => $tournament, 'isVisible' => true],['position'=>'ASC']);
        }else{

            $fights = $em->getRepository(Fight::class)->findBy(['isVisible' => true]);
        }

        $result = $serializer->normalize($fights, 'json');

        return new JsonResponse(['data' => $result]);
    }
}
