<?php

namespace Tests\AppBundle\Security;

use AppBundle\Entity\Facebook;
use AppBundle\Entity\User;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class FacebookTest extends WebTestCase
{
    protected $environment = 'dev';

    /**
     * @test
     */
    public function one()
    {

        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $userRepo = $em->getRepository(Facebook::class);
        $facebook = $userRepo
            ->findOneBy(['facebookId' => '10207742742112227']);

        var_dump($facebook);
    }
}
