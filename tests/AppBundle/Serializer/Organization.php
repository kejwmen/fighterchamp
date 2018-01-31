<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 1/30/18
 * Time: 9:33 PM
 */

namespace Tests\AppBundle\Serializer;

class Organization
{
    private $name;
    private $members;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setMember($members)
    {
        $this->members = $members;
    }

    public function getMember()
    {
        return $this->members;
    }
}