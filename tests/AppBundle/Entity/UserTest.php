<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 12/25/17
 * Time: 6:02 AM
 */

namespace AppBundle\Tests;

use AppBundle\Entity\Foo;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    /**
     * @var UserRepository
     */
    private $em;


    public function setUp()
    {
       $kernel =  self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

    }

    public function testUserEditWithoutSetters()
    {
        $user = new Foo('slawomir.grochowski@gmail.com');

        $user->__construct('mario');

        var_dump($user);
    }


    public function AddCoachWhenThatIsExistingOne()
    {
        /**
         * @var $user User
         */
        $user = $this->em->getRepository(User::class)->find(346);

        $user2 = $this->em->getRepository(User::class)->find(21);

//        $couch = $user->getCoach();

        $user->setUsers($user2);

        $this->em->persist();
        $this->em->flush();

        echo 'duaaaa';
    }

}