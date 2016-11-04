<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 14.08.16
 * Time: 14:49
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FightController extends Controller
{
    /**
     * @Route("/walki", name="fight")
     */
    public function resultAction()
    {

        $em = $this->getDoctrine()->getManager();
        $fights = $em->getRepository('AppBundle:Fight')
            ->fightReadyOrderBy();

        return $this->render('fight/result.html.twig', [
            'fights' => $fights
        ]);


    }

}