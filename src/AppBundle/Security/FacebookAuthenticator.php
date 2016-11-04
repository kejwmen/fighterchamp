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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
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

    public function __construct(ClientRegistry $clientRegistry, EntityManager $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;

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
        $email = $facebookUser->getEmail();

             $user = $this->em->getRepository('AppBundle:User')
            ->findOneBy(array('email' => $email));

        if(!$user) {

            // takes URL of image and Path for the image as parameter
            function download_image1($image_url, $image_file){
                $fp = fopen ($image_file, 'w+');              // open file handle

                $ch = curl_init($image_url);
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
                curl_setopt($ch, CURLOPT_FILE, $fp);          // output to file
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1000);      // some large value to allow curl to run for a long time
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                // curl_setopt($ch, CURLOPT_VERBOSE, true);   // Enable this line to see debug prints
                curl_exec($ch);

                curl_close($ch);                              // closing curl handle
                fclose($fp);                                  // closing file handle
            }

            $url = $facebookUser->getPictureUrl();
            $file_name = 'fb_temp';

            download_image1($url,$file_name);

            $file = new File($file_name,true);
            $ext = $file->getExtension();

            $image_file = new UploadedFile($file_name.$ext, $file_name.$ext, null, null, null, true);

            $user = new User();
            $user->setEmail($email);
            $user->setName($facebookUser->getFirstName());
            $user->setSurname($facebookUser->getLastName());
            $male = $facebookUser->getGender() == 'male' ? true : false;
            $user->setMale($male);
            $user->setImageFile($image_file);
            $user->setFacebookId($facebookUser->getId());
            $this->em->persist($user);
            $this->em->flush();

        }else{
            $existingUser = $this->em->getRepository('AppBundle:User')
                ->findOneBy(array('facebook_id' => $facebookUser->getId()));

            if ($existingUser) {
                return $existingUser;
            }
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
        return new RedirectResponse($this->router->generate('login'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $user = $token->getUser();

        if($user->getbirtDate() OR $user->getphone() == null){
            return new RedirectResponse($this->router->generate('regi'));
        }else {
            return new RedirectResponse($this->router->generate('homepage'));
        }

    }

    public function start(Request $request, AuthenticationException $authException = null)
    {


    }


}