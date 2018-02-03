<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Fight;
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
            $tournament = $em->getRepository('AppBundle:Fight')->find($id);

            $fights = $em->getRepository('AppBundle:Fight')->findBy(['tournament' => $tournament]);
        }else{

            $fights = $em->getRepository('AppBundle:Fight')->findAll();
        }

        $result = $serializer->normalize($fights, 'json');

        return new JsonResponse(['data' => $result]);
    }




}
