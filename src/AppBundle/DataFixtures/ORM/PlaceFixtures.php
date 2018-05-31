<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Place;
use AppBundle\Entity\Tournament;
use Doctrine\Common\Persistence\ObjectManager;


class PlaceFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Place::class, 10, function (Place $place, $count) {

            $place->setCapacity($this->faker->numberBetween(10, 100));
            $place->setCity($this->faker->city);
            $place->setName($this->faker->company);
            $place->setStreet($this->faker->streetName);

        });

        $manager->flush();
    }
}