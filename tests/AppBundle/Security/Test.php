<?php

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class Test extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    public function setUp()
    {
        $kernel = new AppKernel('dev', true);
        $kernel->boot();
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testProduct()
    {
        $mario = $this->em->getRepository('AppBundle:Facebook')->findOneBy(['facebookId' => '2147483647']);

        $user = $mario->getUser();

        echo 'siema';
    }
}
