<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;



/**
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClubRepository")
 * @ORM\Table(name="club")
 */
class Club
{
    public function __construct()
    {
        $this->created_at = new \DateTime('now');
        $this->users = new ArrayCollection();
    }

    /**
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $www;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="club")
     * @var Collection|User[]
     */
    private $users;



    public function getId()
    {
        return $this->id;
    }


    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    public function getName()
    {
        return $this->name;
    }


    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }


    public function getCity()
    {
        return $this->city;
    }


    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }


    public function getStreet()
    {
        return $this->street;
    }


    public function getImageName()
    {
        return $this->imageName;
    }


    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }


    public function getWww()
    {
        return $this->www;
    }


    public function setWww($www)
    {
        $this->www = $www;
    }


    public function getUsers()
    {
        return $this->users;
    }


    public function setUsers($users)
    {
        $this->users = $users;
    }

    public function __toString()
    {
        return $this->name;
    }
}

