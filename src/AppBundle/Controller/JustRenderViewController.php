<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 18.01.17
 * Time: 00:17
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Fight;
use AppBundle\Event\Events;
use AppBundle\Event\UserCreatedEvent;
use AppBundle\Event\UserEvent;
use AppBundle\Form\ContactType;
use AppBundle\Service\AppMailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JustRenderViewController extends Controller
{
    /**
     * @Route("/kontakt", name="contact")
     */
    public function contactController(Request $request, EventDispatcherInterface $eventDispatcher, UrlGeneratorInterface $router)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->get(AppMailer::class)
                ->sendEmail('slawomir.grochowski@gmail.com', 'Kontakt', $form['email']->getData());

            $this->addFlash('success', 'Sukces! Twój twój adres email został wysłany! Sławek niebawem się do Ciebię odezwie ;)');

            return $this->redirect($request->getUri());
        }


        return $this->render('contact/contact.html.twig', [
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

