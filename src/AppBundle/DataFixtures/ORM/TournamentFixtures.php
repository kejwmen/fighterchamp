<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Place;
use AppBundle\Entity\Tournament;
use Doctrine\Common\Persistence\ObjectManager;


class TournamentFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Tournament::class, 10, function (Tournament $tournament, $count) {

            $tournament->setName('Granda ' . $count);
            $tournament->setStart(new \DateTime('now'));

            $tournament->setPlace($this->getReference(Place::class . '_' . $count));

        });

        $manager->flush();
    }
}