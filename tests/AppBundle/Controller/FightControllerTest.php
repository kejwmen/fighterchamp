<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Fight;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FightControllerTest extends KernelTestCase
{
//    public function testDisplay()
//    {
//        $client = static::createClient();
//
//        $crawler = $client->request('GET', '/walki');
//
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertContains('Zawodnik', $crawler->text());
//    }

    /**
     * @var EntityManager
     */
    private $em;

    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testCreate()
    {
       $userRepo =  $this->em->getRepository('AppBundle:User');

       $user1 = $userRepo->find(30);
       $user2 = $userRepo->find(31);

       $fight = new Fight();

        $fight->addUser($user1);
        $fight->addUser($user2);

        $this->em->persist($fight);
        $this->em->flush();

    }




}
