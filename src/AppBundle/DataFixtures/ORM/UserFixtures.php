<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\User;

use Doctrine\Common\Persistence\ObjectManager;


class UserFixtures extends BaseFixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setHash($this->faker->sha1);
        $user->setEmail('admin@admin.pl');
        $user->setName('admin');
        $user->setSurname('admin');
        $user->setMale(true);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPlainPassword('password');

        $manager->persist($user);

        foreach (range(1,10) as $i) {
            $user = new User();
            $user->setHash($this->faker->sha1);
            $user->setEmail($this->faker->email);
            $user->setName($this->faker->firstName);
            $user->setSurname($this->faker->lastName);
            $user->setMale($this->faker->boolean());

            $manager->persist($user);

            $this->addReference(User::class . '_' . $i, $user);

        }

        $manager->flush();
    }

}