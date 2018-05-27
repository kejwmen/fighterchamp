<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 5/26/18
 * Time: 8:17 PM
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\User;

use Doctrine\Common\Persistence\ObjectManager;


class UserFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $user = new User();
        $user->setHash($this->faker->sha1);
        $user->setEmail('admin@admin.pl');
        $user->setName('admin');
        $user->setSurname('admin');
        $user->setMale(true);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPlainPassword('password');

        $this->createMany(User::class, 10, function (User $user, $count){

            $user->setHash($this->faker->sha1);
            $user->setEmail($this->faker->email);
            $user->setName($this->faker->firstName);
            $user->setSurname($this->faker->lastName);
            $user->setMale($this->faker->boolean());

        });

        $manager->flush();
    }
}