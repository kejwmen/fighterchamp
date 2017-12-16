<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use AppBundle\Form\User\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api", condition="request.isXmlHttpRequest()")
 */
class UserController extends Controller
{
    /**
     * @Route("/user-create", name="user_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(UserType::class, $user,
            [
                'entity_manager' => $this->get('doctrine.orm.entity_manager'),
                'is_new_user' => true
            ]
        );


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Sukces! Twój profil został utworzony! Jesteś zalogowany!');

            $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );

            return new JsonResponse(
                ['location' => $this->generateUrl('user_show', ['id' => $user->getId()])], 200);
        }

        return new JsonResponse(
            [
                'form' => $this->renderView('security/user_form.html.twig',
                    [
                        'entity' => $user,
                        'form' => $form->createView(),
                    ])], 400);
    }


    /**
     * @Route("/user-update", name="user_update")
     */
    public function updateAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(UserType::class, new User(),
            [
                'entity_manager' => $this->get('doctrine.orm.entity_manager'),
                'is_new_user' => false
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Sukces! Zmiany na twoim profilu zostały zapisane!!');

            $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );

            return new JsonResponse(200);
        }

        return new JsonResponse(
            [
                'form' => $this->renderView('security/user_form.html.twig',
                    [
                        'entity' => $form->getData(),
                        'form' => $form->createView(),
                    ])], 400);



    }

}