<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 2016-06-19
 * Time: 13:06
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tournament")
 */
class Tournament
{

    public function __construct($userAdmin)
    {
        $this->userAdmin = $userAdmin;
        $this->userAdmin = new ArrayCollection();
        $this->signUpTournament = new ArrayCollection();
        $this->schedule = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacity;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $placeName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $contactGuy;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SignUpTournament", mappedBy="tournament")
     */
    private $signUpTournament;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Schedule", mappedBy="tournament")
     */
    private $schedule;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end;


    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $signUpTill;

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param mixed $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getPlaceName()
    {
        return $this->placeName;
    }

    /**
     * @param mixed $placeName
     */
    public function setPlaceName($placeName)
    {
        $this->placeName = $placeName;
    }

    /**
     * @ORM\OneToMany(
     *     targetEntity="UserAdminTournament",
     *     cascade={"persist"},
     *     mappedBy="tournament")
     */
    private $userAdmin;

    /**
     * @return mixed
     */
    public function getUserAdmin()
    {
        return $this->userAdmin;
    }

///coś tu jest pokręcone trzeba to zmienić
    public function addUserAdmin(UserAdminTournament $userTournamentAdmin)
    {

        if ($this->userAdmin->contains($userTournamentAdmin)) {
            return;
        }

        $this->userAdmin[] = $userTournamentAdmin;
        // needed to update the owning side of the relationship!
        $userTournamentAdmin->setUser($this);
    }


    public function __toString()
    {
        return (string) $this->getName();
    }


    /**
     * @return mixed
     */
    public function getSignUpTournament()
    {
        return $this->signUpTournament;
    }

    /**
     * @param mixed $signUpTournament
     */
    public function setSignUpTournament($signUpTournament)
    {
        $this->signUpTournament = $signUpTournament;
    }

    /**
     * @return mixed
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @return mixed
     */
    public function getSignUpTill()
    {
        return $this->signUpTill;
    }

    /**
     * @param mixed $signUpTill
     */
    public function setSignUpTill($signUpTill)
    {
        $this->signUpTill = $signUpTill;
    }











}

