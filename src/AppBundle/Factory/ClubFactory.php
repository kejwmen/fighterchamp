<?php

declare(strict_types = 1);

namespace AppBundle\Factory;

use AppBundle\Entity\Club;

class ClubFactory
{
    public static function createClub($name, $city, $street, $website): Club
    {
        $club = new Club();
        $club->setName($name);
        $club->setCity($city);
        $club->setStreet($street);
        $club->setWww($website);

        return $club;
    }
}