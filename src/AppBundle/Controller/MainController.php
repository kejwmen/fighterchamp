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

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function resultAction()
    {

        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('AppBundle:News')
            ->findBy(array(), array('date' => 'DESC'));


        return $this->render(':main:homepage.html.twig', [
            'news' => $news
        ]);


    }
}