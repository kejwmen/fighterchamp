<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 25.09.16
 * Time: 17:09
 */

namespace AppBundle\Controller;

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
        $news = $em->getRepository('AppBundle:News')
            ->findBy(array(), array('date' => 'DESC'));

        $tournament = $em->getRepository('AppBundle:Tournament')
            ->find(4);

        return $this->render(':main:homepage.html.twig', [
            'news' => $news,
            'tournament' => $tournament
        ]);
    }
}