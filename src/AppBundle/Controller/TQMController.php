<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 15.04.17
 * Time: 22:47
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TQMController extends Controller
{
    /**
     * @Route("/tqm", name="tqm_nowosci")
     */
    public function listAction()
    {

        $em = $this->getDoctrine()->getManager();

        $tasks = $em->getRepository('AppBundle:Task')->findAllTasks();

        $ideas = $em->getRepository('AppBundle:Task')->findAllIdeas();



        return $this->render('tqm/news.html.twig', [
            'tasks' => $tasks,
            'ideas' => $ideas,
            'test' => true
        ]);
    }

///condition="request.isXmlHttpRequest()

    /**
     * @Route("/tqm/task/{id}", name="tqm_i_will_help")
     */
    public function iWillHelpAction(Task $task)
    {
        $user = null;
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $user = $this->getUser();
        }

        return new Response('Siema');


    }


}