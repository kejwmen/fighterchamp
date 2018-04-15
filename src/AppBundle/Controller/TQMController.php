<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 15.04.17
 * Time: 22:47
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\UserTask;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TQMController extends Controller
{
    /**
     * @Route("/tqm", name="tqm_nowosci")
     */
    public function listAction(EntityManagerInterface $em)
    {
        $tasks = $em->getRepository('AppBundle:Task')->findAll();

//
//        $serializer = $this->get('serializer.my');
//        $json = $serializer->serialize($tasks,'json');

        dump($tasks);

        return $this->render('tqm/news.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/tqm/task/{id}", options={"expose"=true}, name="tqm_i_will_help", condition="request.isXmlHttpRequest()")
     */
    public function iWillHelpAction(Task $task)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $user = $this->getUser();

            $taskUser = new UserTask();
            $taskUser->setUser($user);
            $taskUser->setIdea(false);
            $taskUser->setTask($task);

            $em = $this->getDoctrine()->getManager();
            $em->persist($taskUser);
            $em->flush();

            return new Response(200);
        }

        return new Response(403);
    }


}