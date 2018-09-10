<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class RankController extends Controller
{
    /**
     * @Route("ranking", name="rank")
     */
    public function indexAction(EntityManagerInterface $entityManger)
    {
        $users = $entityManger->getRepository(User::class)->findAllFightersRank();

        return $this->render('rank/rank.html.twig', [
            'users' => $users
        ]);
    }
}
