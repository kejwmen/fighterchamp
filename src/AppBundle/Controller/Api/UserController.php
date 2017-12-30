<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use AppBundle\Form\User\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


// condition="request.isXmlHttpRequest()

/**
 * @Route("/api")
 */
class UserController extends Controller
{
    /**
     * @Route("/user-create", name="user_create")
     * @Method("POST")
     */
    public function createAction(Request $request, EntityManagerInterface $em)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);


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
                'form' => $this->renderView('user/user_form.html.twig',
                    [
                        'form' => $form->createView(),
                    ])], 400);
    }


    /**
     * @Route("/user-update", name="user_update")
     */
    public function updateAction(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $formUser = $form->getData();

            $em->persist($formUser);
            $em->flush();

            $this->addFlash('success', 'Sukces! Zmiany na twoim profilu zostały zapisane!!');

            $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $formUser,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );

            return new JsonResponse(200);
        }

        return new JsonResponse(
            [
                'form' => $this->renderView('user/user_form.html.twig',
                    [
                        'form' => $form->createView(),
                    ])], 400);
    }

    /**
     * @Route("/ludzie", name="api_user_list")
     * @Method("POST")
     */
    public function listAction(Request $request, EntityManagerInterface $em)
    {
        $userType = $request->request->get('userType');


        $users = $em->getRepository(User::class)->findBy(['type' => $userType]);

        if($userType == 1)
        {
            $user = 'fighter';
        }elseif($userType == 2)
        {
            $user = 'coach';
        }else{
            $user = 'fan';
        }

        return $this->render("user/$user/_list.html.twig",
            [
                'users' => $users
            ]);

    }


}