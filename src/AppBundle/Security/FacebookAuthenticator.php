<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 06.08.16
 * Time: 09:51
 */

namespace AppBundle\Security;

use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\User;



class FacebookAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;
    private $container;

    public function __construct(ClientRegistry $clientRegistry,
        ContainerInterface $container, EntityManager $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->container = $container;

    }


    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/connect/facebook/check') {

            return null;
        }

        return $this->fetchAccessToken($this->getFacebookClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()->fetchUserFromToken($credentials);

        $user = $this->em->getRepository('AppBundle:User')
                    ->findOneBy(['facebookId' => $facebookUser->getId()]);

        if(!$user){

            /** @var $session Session **/
            $session = $this->container->get('session');

            $session->set('facebookId', $facebookUser->getId());
            $session->set('name', $facebookUser->getFirstName());
            $session->set('surname', $facebookUser->getLastName());
            $session->set('male', $facebookUser->getGender() == 'male' ? true : false);
            $session->set('imageName', $facebookUser->getPictureUrl());
            $session->set('email', $facebookUser->getEmail());

            $session->save();

            throw new AuthenticationException();
        }


        return $user;

    }

    /**
     * @return FacebookClient
     */
    private function getFacebookClient()
    {
        return $this->clientRegistry
            // "facebook_main" is the key used in config.yml
            ->getClient('facebook_main');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {

        return new RedirectResponse($this->container->get('router')
            ->generate('register_fb'));

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $user = $token->getUser();

            return new RedirectResponse($this->router->generate('homepage'));
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {


    }


}