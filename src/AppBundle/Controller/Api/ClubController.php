<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Club;
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

        foreach ($clubs as &$club){
            $club['record']= $this->countRecordClub($club[0]);
        }

        $serializer = $this->get('serializer_club');

        $clubs = $serializer->serialize(['data' => $clubs], 'json');

        return new Response($clubs, 200, ['Content-Type' => 'application/json']);
    }

    private function countRecordClub(Club $club): array
    {
        $win = $draw = $lose = 0;

        foreach ($club->getUsers() as $user){
            foreach ($user->getUserFights() as $userFight){
                if (($userFight->getFight())->getWinner()){
                    if($user == ($userFight->getFight()->getWinner()))
                    {
                        $win++;
                    }else{
                        $lose++;
                    }
                }elseif (($userFight->getFight())->getDraw()){
                    $draw++;
                }

            }
        }

        return ['W' => $win, 'D' => $draw, 'L' => $lose];
    }

}
