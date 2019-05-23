<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 24.09.16
 * Time: 18:49
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Tournament;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * @Route("/admin")
 */
class MailController extends Controller
{

    /**
     * @Route("/mail", name="admin_mail")
     */
    public function mailAction()
    {
        $user = $this->getUser();
        $username = $user->getUsername();

        if($username != 'admin') {
            $this->denyAccessUnlessGranted('slawek'); // just gibberish
        }

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')
            ->findAll();

        $tournament = $em->getRepository(Tournament::class)
            ->findNewestOne();

        $sign_up_users = $em->getRepository('AppBundle:SignUpTournament')
             ->findAllForTournament($tournament);

        return $this->render('admin/mail.html.twig', [
            'users' => $users,
            'usersQty' => count($users),
            'sign_up_users' => $sign_up_users,
            'sing_up_usersQty' => count($sign_up_users)
        ]);
    }
}

    /*
    public function sendMail(Request $request)
    {
        $to = $request->request->get('to');
        $text = $request->request->get('text');
        $topic = $request->request->get('topic');

        $appmailer = $this->get('appmailer');

        $appmailer->sendEmail(
            $to,
            $topic,
            $text);

        return $this->redirectToRoute('admin_user_list');
    }
    */
