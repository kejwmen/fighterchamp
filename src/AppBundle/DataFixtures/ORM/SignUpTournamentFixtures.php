<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 6/2/18
 * Time: 10:19 AM
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class SignUpTournamentFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $x = [$this->getReference(User::class . '_' . $count), $this->getReference(Tournament::class . '_' . $count)];

        $this->createMany(SignUpTournament::class, 20, function (SignUpTournament $signUpTournament, $count) {

            $signUpTournament->setWeight($this->faker->numberBetween(50,100));
            $signUpTournament->setFormula($this->faker->randomElement('A', 'B', 'C'));

        });

        $manager->flush();
    }
}