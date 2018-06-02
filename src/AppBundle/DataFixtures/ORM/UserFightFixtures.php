<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Fight;
use AppBundle\Entity\Place;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\UserFight;
use Doctrine\Common\Persistence\ObjectManager;


class UserFightFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(UserFight::class, 20, function (UserFight $userFight, $count) {


            $userFight->

            $userFight->setFight($this->getReference(UserFight::class . '_' . $count));

        });

        $manager->flush();
    }
}