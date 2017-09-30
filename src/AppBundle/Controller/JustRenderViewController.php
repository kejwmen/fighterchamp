<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 18.01.17
 * Time: 00:17
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Fight;
use AppBundle\Form\ContactType;
use AppBundle\Service\AppMailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JustRenderViewController extends Controller
{
    /**
     * @Route("/kontakt", name="contact")
     */
    public function contactController(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $userRepo =  $em->getRepository('AppBundle:User');

        $user1 = $userRepo->find(30);
        $user2 = $userRepo->find(31);

        $fight = new Fight();

        $fight->addUser($user1);
        $fight->addUser($user2);

        $fight->setTournament($em->getRepository('AppBundle:Tournament')->find(3));

        $em->persist($fight);
        $em->flush();



        $form = $this->createForm(ContactType::class, null);

            $form->handleRequest($request);

            if($form->isValid()){

                $this->get(AppMailer::class)
                    ->sendEmail('slawomir.grochowski@gmail.com','Kontakt',$form['email']->getData());

                $this->addFlash('success', 'Sukces! Twój twój adres email został wysłany! Sławek niebawem się do Ciebię odezwie ;)');

                    return $this->redirect($request->getUri());
                }


        return $this->render('contact/contact.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/regulamin", name="rules")
     */
    public function rulesController()
    {
        return $this->render('rules/rules.html.twig');
    }

}

