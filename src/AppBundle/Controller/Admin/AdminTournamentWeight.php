<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 05.02.17
 * Time: 10:57
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Tournament;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */
class AdminTournamentWeight extends Controller
{
    /**
     * @Route("/set-weighted", name="set_weighted")
     */
    public function setWeighted(Request $request)
    {
        $signUpId = $request->request->get('signUpId');
        $weighted = $request->request->get('weighted');

        $em = $this->getDoctrine()->getManager();
        $signUp = $em->getRepository('AppBundle:SignUpTournament')
            ->findOneBy(['id' => $signUpId]);

        $signUp->setWeighted($weighted);
        $em->flush();

        return new Response(200);
    }
}