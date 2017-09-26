<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 2016-06-21
 * Time: 07:13
 */
declare(strict_types = 1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FightRepository")
 * @ORM\Table(name="fight")
 */
class Fight
{

    public function __construct()
    {
        $this->created_at = new \DateTime('now');
        $this->users = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\SignUpTournament", inversedBy="fights")
     * @ORM\JoinTable(name="signuptournament_fight")
     * */
    private $signuptournament;

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
     * @ORM\Column(type="string",nullable=true)
     */
    private $formula;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ready = false;


    /**
     * @ORM\Column(type="boolean")
     */
    private $draw = false;


    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $day;


    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $youtubeId;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="fights")
     * @ORM\JoinTable(name="user_fight")
     */
    private $users;

    /**
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @return mixed
     */
    public function getSignuptournament()
    {
        return $this->signuptournament;
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id)
    {
        $this->id = $id;
    }


    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getUsers()
    {
        return $this->users;
    }


    public function getReady(): bool
    {
        return $this->ready;
    }


    public function setReady(bool $ready)
    {
        $this->ready = $ready;
    }

    public function toggleReady()
    {
        $this->ready = $this->ready ? false : true;
    }


    public function getTournament()
    {
        return $this->tournament;
    }


    public function setTournament($tournament)
    {
        $this->tournament = $tournament;
    }

    public function resetWinner()
    {
        $this->winner = null;
    }


    public function getWinner()
    {
        return $this->winner;
    }


    public function setWinner(User $winner)
    {
        $this->winner = $winner;
    }


    public function getFormula()
    {
        return $this->formula;
    }


    public function setFormula(string $formula)
    {
        $this->formula = $formula;
    }


    public function getWeight()
    {
        return $this->weight;
    }


    public function setWeight(string $weight)
    {
        $this->weight = $weight;
    }


    public function getPosition(): ?int
    {
        return $this->position;
    }


    public function setPosition(int $position)
    {
        $this->position = $position;
    }


    public function getDraw(): bool
    {
        return $this->draw;
    }


    public function setDraw(bool $draw)
    {
        $this->draw = $draw;
    }

    public function resetDraw()
    {
        $this->draw = false;
    }


    public function __toString()
    {
        return (string)$this->getFormula();
    }


    public function getDay(): \DateTime
    {
        return $this->day;
    }


    public function setDay(\DateTime $day)
    {
        $this->day = $day;
    }

    public function getYoutubeId(): ?string
    {
        return $this->youtubeId;
    }

    public function setYoutubeId(string $youtubeId)
    {
        $this->youtubeId = $youtubeId;
    }












}