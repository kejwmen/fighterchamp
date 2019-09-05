<?php

declare(strict_types = 1);

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="club")
 */
class Club
{
    use TimestampableTrait;

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
     */
    private $users;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->users = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function setCity(string $city)
    {
        $this->city = $city;

        return $this;
    }


    public function getCity(): string
    {
        return $this->city;
    }


    public function setStreet(string $street)
    {
        $this->street = $street;

        return $this;
    }


    public function getStreet(): string
    {
        return $this->street;
    }


    public function getImageName(): string
    {
        return $this->imageName;
    }


    public function setImageName(string $imageName)
    {
        $this->imageName = $imageName;
    }


    public function getWww(): string
    {
        return $this->www;
    }


    public function setWww(string $www)
    {
        $this->www = $www;
    }

    /**
     * @return User[]|Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClub($this);
        }
        return $this;
    }
}
