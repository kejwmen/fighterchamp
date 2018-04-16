<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 15.04.17
 * Time: 22:47
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Task;
use AppBundle\Entity\UserTask;
use AppBundle\Form\CommentType;
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
    public function listAction(EntityManagerInterface $em, Request $request)
    {
        $user = $this->getUser();

        $tasks = $em->getRepository(Task::class)->findAll();
        $comments = $em->getRepository(Comment::class)->findBy([],['createdAt' => 'DESC']);

        $form = $this->createForm(CommentType::class, new Comment());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var $comment Comment
             */
            $comment = $form->getData();
            $comment->setUser($user);

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('tqm_nowosci');
        }

        return $this->render('tqm/news.html.twig', [
            'tasks' => $tasks,
            'comments' => $comments,
            'form' => $form->createView()
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