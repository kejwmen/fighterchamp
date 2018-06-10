<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 6/1/18
 * Time: 10:33 AM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Repository\SignUpTournamentRepository;
use AppBundle\Service\FightService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class FightServiceTest extends TestCase
{
    public function testCreateFight()
    {
        $user0 = new User();
        $user0->setName('User 0');

        $user1 = new User();
        $user1->setName('User 1');

        $tournament = new Tournament();
        $tournament->setName('Tuournament');
        $tournament->setStart(new \DateTime('now'));

        $signUp0 = new SignUpTournament($user0, $tournament);
        $signUp0->setFormula('A');
        $signUp0->setWeight('80');

        $signUp1 = new SignUpTournament($user1, $tournament);
        $signUp1->setFormula('A');
        $signUp1->setWeight('80');

        $signUpRepository = $this->createMock(SignUpTournamentRepository::class);

        $signUpRepository->expects($this->any())
            ->method('find')
            ->will($this->onConsecutiveCalls($signUp0, $signUp1));

        $entityManager = $this->createMock(EntityManager::class);

        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($signUpRepository);

        $data = ['ids' => [1,2]];

        $fightService = new FightService($entityManager);
        $fight = $fightService->createFight($data);

        $this->assertEquals($tournament, $fight->getTournament());
        $this->assertEquals('A', $fight->getFormula());
        $this->assertEquals('80', $fight->getWeight());
    }
}
