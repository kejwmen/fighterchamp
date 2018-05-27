<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 5/26/18
 * Time: 8:17 PM
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $user = new User();
        $user->setHash($faker->sha1);
        $user->setEmail('admin@admin.pl');
        $user->setName('admin');
        $user->setSurname('admin');
        $user->setMale(true);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPlainPassword('password');
        $manager->persist($user);

        foreach (range(1,20) as $i)
        {
            $user = new User();
            $user->setHash($faker->sha1);
            $user->setEmail($faker->email);
            $user->setName($faker->firstName);
            $user->setSurname($faker->lastName);
            $user->setMale($faker->boolean());

            $manager->persist($user);
        }


        $manager->flush();
    }
}