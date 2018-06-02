<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Fight;
use AppBundle\Entity\Place;
use AppBundle\Entity\Tournament;
use Doctrine\Common\Persistence\ObjectManager;


class FightFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $x = ['A', '80'];

        $this->createMany(Fight::class, 10, function (Fight $fight, $count, $x) {


//            $fight->set($this->getReference(Place::class . '_' . $count));

        });

        $manager->flush();
    }
}