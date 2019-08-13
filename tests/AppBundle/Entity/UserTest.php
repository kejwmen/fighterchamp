<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 12/25/17
 * Time: 6:02 AM
 */

namespace AppBundle\Tests;

use AppBundle\Entity\User;
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

    public function setUp()
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();
        $this->em = $kernel->getContainer()->get('doctrine')->getManager();
        $this->userBuilder = new UserBuilder();
        $this->databaseHelper = new DatabaseHelper(new Database());
        $this->databaseHelper->truncateAllTables();
    }

    public function testUserEditWithoutSetters()
    {

    }


    /**
     * @test
     */
    public function add_coach_to_fighter()
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

        $fighter->setUsers($coach);

        $this->em->flush();

        $this->assertEquals('Coach 1', $fighter->getCoach()->getName());

        $this->assertEquals('Fighter 1', $coach->getUsers()->first()->getName());
    }

    /**
     * @test
     */
    public function add_coach_to_coach()
    {
        $coach1 = $this->userBuilder
            ->withType(User::TYPE_COACH)
            ->withName('Coach 1')
            ->build();

        $coach2 = $this->userBuilder
            ->withType(User::TYPE_COACH)
            ->withName('Coach 2')
            ->build();

        $this->em->persist($coach1);
        $this->em->persist($coach2);

        $coach1->setUsers($coach2);

        $this->em->flush();

        $this->assertEquals('Coach 2', $coach1->getCoach()->getName());

        $this->assertEquals('Coach 1', $coach2->getUsers()->first()->getName());
    }
}