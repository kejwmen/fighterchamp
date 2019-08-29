<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Fight;
use AppBundle\Entity\Place;
use AppBundle\Entity\Tournament;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class FightFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        foreach (range(1, 50) as $i) {

                $fight = new Fight(
                    $this->faker->randomElement(['A', 'B', 'C']),
                    $this->faker->numberBetween(50, 100)
                );

                $tournamentReference = ($i % 10 === 0)? 10 : $i % 10;

                $fight->setTournament($this->getReference(Tournament::class . '_' . $tournamentReference));
                $fight->setIsVisible(true);
                $fight->setDay(new \DateTime);

                $manager->persist($fight);

                $this->addReference(Fight::class . '_' . $i, $fight);

            }


        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TournamentFixtures::class,
        );
    }

}