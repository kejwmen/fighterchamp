<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 25.09.16
 * Time: 17:09
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function resultAction()
    {

        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('AppBundle:News')
            ->findBy(array(), array('date' => 'DESC'));

        $tournament = $em->getRepository('AppBundle:Tournament')
            ->find(2);

        $fights = $em->getRepository('AppBundle:Fight')
            ->findAllFightsForTournament($tournament);

        return $this->render(':main:homepage.html.twig', [
            'news' => $news,
            'fights' => $fights
        ]);


    }
}