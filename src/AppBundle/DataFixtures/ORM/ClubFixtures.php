<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Factory\ClubFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use AppBundle\Entity\User;

class ClubFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1, 2) as $i) {
            $club = ClubFactory::createClub(
                $this->faker->company,
                $this->faker->city,
                $this->faker->streetAddress,
                $this->faker->url
            );
            if ($i === 1) {
                foreach (range(1, 2) as $i) {
                    /** @var User $user */
                    $user = $this->getReference(User::class . '_' . $i);
                    $club->addUser($user);
                }
            }

            if ($i === 2) {
                foreach (range(3, 10) as $i) {
                    /** @var User $user */
                    $user = $this->getReference(User::class . '_' . $i);
                    $club->addUser($user);
                }
            }


            $manager->persist($club);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
