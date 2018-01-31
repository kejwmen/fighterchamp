<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 1/30/18
 * Time: 9:35 PM
 */

namespace Tests\AppBundle\Serializer;


class Member
{
    private $name;
    private $organization;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setOrganization(Organization $organization)
    {
        $this->organization = $organization;
    }

    public function getOrganization()
    {
        return $this->organization;
    }
}