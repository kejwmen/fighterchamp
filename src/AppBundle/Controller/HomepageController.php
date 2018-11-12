<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function resultAction(EntityManagerInterface $em)
    {
//        $news = $em->getRepository('AppBundle:News')
//            ->findBy(array(), array('date' => 'DESC'));
//
        $tournament = $em->getRepository(Tournament::class)
            ->find(8);

        return $this->render(':main:homepage.html.twig', [
//            'news' => $news,
            'tournament' => $tournament
        ]);
    }
}