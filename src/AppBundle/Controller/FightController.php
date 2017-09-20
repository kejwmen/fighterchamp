<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 7/29/17
 * Time: 2:08 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Fight;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/walki")
 */
class FightController extends Controller
{

    /**
     * @Route("/{id}", name="fight_show")
     */
    public function showAction(Fight $fight)
    {

        return $this->render('fight/show.html.twig',
            [
                'fight' => $fight,
            ]);
    }

    /**
     * @Route("", name="fight_list")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $fights = $em->getRepository(Fight::class)->findBy(['ready' => true],['youtubeId' => 'DESC']);

        return $this->render('fight/list.html.twig',
            [
                'fights' => $fights,
            ]);
    }


}