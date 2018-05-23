<?php

use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Entity\UserFight;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use PHPUnit\Framework\TestCase;

class CreateNewFightTest extends TestCase
{

    public function testCreateNewFight()
    {
        $this->markTestSkipped();

        $kernel = new AppKernel('test', true);
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine')->getManager();

        $purger = new ORMPurger($em);
        $purger->purge();

        $tournament = new Tournament();
        $tournament->setName('Warszawa');
        $tournament->setStart(new DateTime());
        $em->persist($tournament);
        $em->flush();

        $fight1 = new Fight('boks','100');
        $fight1->setTournament($tournament);

        $user1 = new User(true);
        $user1->setEmail('mario@o2.pl');
        $user1->setName('Mariioo');
        $user1->setSurname('marrrr');

        $userFight = new UserFight(
            $user1, $fight1
        );

        $em->persist($user1);
        $em->flush();

        $fight1->addUsersFight($userFight);


        $em->persist($fight1);
        $em->flush();
    }

}
