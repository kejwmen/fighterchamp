<?php

namespace AppBundle\Tests;

use AppBundle\Entity\User;
use AppBundle\Entity\UserCoach;
use AppKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Builder\UserBuilder;
use Tests\Database;
use Tests\DatabaseHelper;

class UserTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserBuilder
     */
    private $userBuilder;

    /**
     * @var DatabaseHelper
     */
    private $databaseHelper;

    function setUp()
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();
        $this->em = $kernel->getContainer()->get('doctrine')->getManager();
        $this->userBuilder = new UserBuilder();
        $this->databaseHelper = new DatabaseHelper(new Database());
        $this->databaseHelper->truncateAllTables();
    }

    /* @test */ function add_coach_to_fighter()
    {
        $fighter = $this->userBuilder
            ->withName('Fighter 1')
            ->build();

        $coach = $this->userBuilder
            ->withType(User::TYPE_COACH)
            ->withName('Coach 1')
            ->build();

        $this->em->persist($fighter);
        $this->em->persist($coach);

        $userCoach = new UserCoach($fighter, $coach);
        $this->em->persist($userCoach);

        $this->em->flush();

        $this->em->refresh($fighter);
        $this->em->refresh($coach);

        $this->assertNotEmpty($fighter->getCoaches());
        $this->assertNotEmpty($coach->getFighters());
    }
}