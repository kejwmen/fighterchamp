<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 2016-06-21
 * Time: 07:13
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FightRepository")
 * @ORM\Table(name="fight")
 *
 */
class Fight
{

    public function __construct()
    {
        $this->setReady(false);
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="fights")
     * @ORM\JoinColumn(name="user_one_id", nullable=false)
     * @Assert\NotBlank()
     */
    private $userOne;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="additionalFights")
     */
    private $userTwo;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tournament")
     * @ORM\JoinColumn(name="tournament_id", nullable=false)
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="winner_id", nullable=true)
     */
    private $winner;

    /**
     * @ORM\Column(type="string")
     */
    private $formula;

    /**
     * @ORM\Column(type="string")
     */
    private $weight;


    /**
     * @ORM\Column(type="integer")
     */
    private $position;


    /**
     * @ORM\Column(type="boolean")
     */
    private $ready;

    /**
     * @return mixed
     */
    public function getReady()
    {
        return $this->ready;
    }

    /**
     * @param mixed $ready
     */
    public function setReady($ready)
    {
        $this->ready = $ready;
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
    public function getUserOne()
    {
        return $this->userOne;
    }

    /**
     * @param mixed $userOne
     */
    public function setUserOne($userOne)
    {
        $this->userOne = $userOne;
    }

    /**
     * @return mixed
     */
    public function getUserTwo()
    {
        return $this->userTwo;
    }

    /**
     * @param mixed $userTwo
     */
    public function setUserTwo($userTwo)
    {
        $this->userTwo = $userTwo;
    }


    /**
     * @return mixed
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * @param mixed $tournament
     */
    public function setTournament($tournament)
    {
        $this->tournament = $tournament;
    }

    public function resetWinner()
    {
        $this->winner = NULL;
    }

    /**
     * @return mixed
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param mixed $winner
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
    }


    /**
     * @return mixed
     */
    public function getFormula()
    {
        return $this->formula;
    }

    /**
     * @param mixed $formula
     */
    public function setFormula($formula)
    {
        $this->formula = $formula;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }


    public function __toString()
    {
        return (string)$this->getFormula();
    }


}