<?php

use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;



class LoadFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = new Nelmio\Alice\Loader\NativeLoader();
        $objectSet = $loader->loadFile(__DIR__.'/Fixtures.yml')->getObjects();
        foreach($objectSet as $object) {
            $manager->persist($object);
        }
        $manager->flush();
    }
}