<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 24.09.16
 * Time: 18:49
 */

namespace AppBundle\Controller\Admin;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


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
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')
            ->findAll();

        $usersQty = count($users);

           $sign_up_users = $em->getRepository('AppBundle:SignUpTournament')
             ->findAll();

        $sing_up_usersQty = count($sign_up_users);

        return $this->render('admin/mail.html.twig', [
            'users' => $users,
            'usersQty' => $usersQty,
            'sign_up_users' => $sign_up_users,
            'sing_up_usersQty' => $sing_up_usersQty
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
