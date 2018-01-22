<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 12/25/17
 * Time: 6:02 AM
 */

namespace AppBundle\Tests;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use AppKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
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

    public function testUserEditWithoutSetters()
    {

    }


    public function testAddCoachWhenThatIsExistingOne()
    {
        /**
         * @var $user User
         */
        $user = $this->em->getRepository('AppBundle:User')->find(18);

        $user2 = $this->em->getRepository('AppBundle:User')->find(19);


        $user->addUser($user2);

        $this->em->persist($user);
        $this->em->flush();

        var_dump('duaaaa');
    }

}