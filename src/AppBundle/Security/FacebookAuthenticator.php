<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 06.08.16
 * Time: 09:51
 */
declare(strict_types=1);

namespace AppBundle\Security;

use AppBundle\Entity\Facebook;
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

        file_put_contents('file', $facebookUser);

//        $id = $facebookUser->getId();
//
//        $facebook = $this->em->getRepository(Facebook::class)
//                    ->findOneBy(['facebookId' => $id]);
//
//        $user = $facebook ? $facebook->getUser() : null;
//
//        //todo 4 argument sometimes is missing
//        if(!$user){
//            $facebook = new Facebook(
//                    $facebookUser->getId(),
//                    $facebookUser->getFirstName(),
//                    $facebookUser->getLastName(),
//                    $facebookUser->getEmail(),
//                    $facebookUser->getGender() === 'male'
//                );
//
//
//            $user = $this->em->getRepository(User::class)
//                ->findOneBy(['email' => $facebookUser->getEmail()]);
//
//            if(!$user) {
//                $user = new User();
//                $user->setName($facebook->getName());
//                $user->setSurname($facebook->getSurname());
//                $user->setEmail($facebook->getEmail());
//                $user->setMale($facebook->isMale());
//                $user->setType(3);
//                $user->setHash(hash('sha256', md5((string)rand())));
//
//                $this->em->persist($user);
//                $this->em->persist($facebook);
//                $facebook->setUser($user);
//            }else{
//                $user->setFacebook($facebook);
//            }
//
//            $this->em->flush();
//        }

        return new User();
    }


    private function getFacebookClient()
    {
        return $this->clientRegistry
            // "facebook_main" is the key used in config.yml
            ->getClient('facebook_main');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new RedirectResponse($this->container->get('router')->generate('user_create_view'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('user_edit_view'));
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {


    }


}