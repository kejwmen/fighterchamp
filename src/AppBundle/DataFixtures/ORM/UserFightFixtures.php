<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Enum\UserFightResult;
use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use AppBundle\Entity\UserFight;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class UserFightFixtures extends BaseFixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        foreach (range(1, 100) as $i) {

            $userReference = ($i % 10 === 0) ? 10 : $i % 10;
            $fightReference = round($i / 2);

            $userFight = new UserFight(
                $this->getReference(User::class . '_' . $userReference),
                $this->getReference(Fight::class . '_' . $fightReference)
            );

            if($i % 2 === 0 ) {
                $userFight->setResult(UserFightResult::WIN());
            };


            $manager->persist($userFight);
        }


        $manager->flush();
    }


    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            FightFixtures::class
        );
    }
}