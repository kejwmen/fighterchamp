<?php

use Tests\AppBundle\Serializer\Member;
use Tests\AppBundle\Serializer\Organization;


class TestSerializer extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Serializer\Serializer
     */
    private $serializer;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function setUp()
    {
        $kernel = new AppKernel('dev', true);
        $kernel->boot();
        $this->serializer = $kernel->getContainer()->get('serializer.user');
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->container = $kernel->getContainer();
    }

    public function testOne()
    {
        $member = new Member();
        $member->setName('Kévin');

        $org = new Organization();
        $org->setName('Les-Tilleuls.coop');
        $org->setMembers(array($member));

        $member->setOrganization($org);

        var_dump($this->serializer->serialize($org, 'json')); // Throws a CircularReferenceException
    }

    public function testOneOne()
    {
        $member = new Member();
        $member->setName('Kévin');

        $org = new Organization();
        $org->setName('Les-Tilleuls.coop');
        $org->setMember($member);

        $member->setOrganization($org);

        var_dump($this->serializer->serialize($member, 'json')); // Infinity loop
    }


    public function testTwo()
    {
        $object = $this->em->getRepository('AppBundle:User')->find(18);

        var_dump($this->serializer->serialize($object, 'json')); // Throws a CircularReferenceException
    }




}
