<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 5/27/18
 * Time: 5:58 PM
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Place;
use AppBundle\Entity\Tournament;
use Doctrine\Common\Persistence\ObjectManager;


class TournamentFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Tournament::class, 10, function (Tournament $tournament, $count) {

            $tournament->setName('Granda ' . $count);
            $tournament->setStart(new \DateTime('now'));

            $place = new Place();
            $place->setCapacity($this->faker->numberBetween(10, 100));
            $place->setCity($this->faker->city);
            $place->setName($this->faker->company);
            $place->setStreet($this->faker->streetName);

            $tournament->setPlace($place);

        });

        $manager->flush();
    }
}