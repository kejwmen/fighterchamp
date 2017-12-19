<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        Fixtures::load(__DIR__ . '/Fixtures.yml', $manager,

            [
                'providers' => [$this]
            ]
        );

    }


    public function weight()

    {
        $weight = [
            '51.0',
            '54.0',
            '57.0',
            '51.0',
            '54.0',
            '63.5',
            '60.0',
            '67.0',
            '71.0',
            '75.0+',
            '75.0',
            '81.0',
            '81.0+',
            '86.0',
            '91.0',
            '91.0+'
        ];
        $key = array_rand($weight);

        return $weight[$key];
    }
}