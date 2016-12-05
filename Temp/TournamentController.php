<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 02.12.16
 * Time: 22:46
 */

namespace AppBundle\Controller\Admin;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin")
 */
class TournamentController extends Controller
{
    /**
     * @Route("/turniej", name="admin_tournament")
     */
    public function TournamentEdit()
    {
        $em = $this->getDoctrine()->getManager();
        $tournaments = $em->getRepository('AppBundle:Tournament')
            ->findAll();

dump($tournaments);

        return $this->render('admin/tournament.html.twig',
            [
                'tournaments' => $tournaments
            ]);
    }

}