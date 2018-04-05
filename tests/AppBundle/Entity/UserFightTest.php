<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 2/4/18
 * Time: 1:27 PM
 */

namespace AppBundle\Tests;

use AppBundle\Entity\Enum\UserFightResult;
use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use AppBundle\Entity\UserFight;
use AppKernel;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;


class UserFightTest extends TestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function setUp()
    {
        $kernel = new AppKernel('dev', true);
        $kernel->boot();
        $this->em = $kernel->getContainer()->get('doctrine')->getManager();

    }

    public function testOne()
    {
        $userFight = new UserFight();
        $userFight->setResult(UserFightResult::DRAW());

        $this->assertEquals('draw',$userFight->getResult());
    }

    public function testGetOpponentUserFight()
    {
        $fight = new Fight('A', 90);

        $user1 = new User();
        $user1->setName('user1');

        $user2 = new User();
        $user2->setName('user2');

        $userFight1 = new UserFight($user1, $fight);
        $userFight2 = new UserFight($user2, $fight);
    }

    public function testGetOpponentUserFightDB()
    {
        /**
         * @var $user User
         */
        $userFight = $this->em->getRepository(UserFight::class)->find(77);


        $this->assertEquals(63, ($userFight->getOpponentUserFight()->getId()));


    }
}
