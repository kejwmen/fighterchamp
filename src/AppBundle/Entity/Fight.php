<?php
declare(strict_types = 1);

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FightRepository")
 * @ORM\Table(name="fight")
 */
class Fight
{
    use TimestampableTrait;

    public function __construct(string $formula, string $weight)
    {
        $this->formula = $formula;
        $this->weight = $weight;
        $this->usersFight = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tournament", inversedBy="fights")
     * @ORM\JoinColumn(nullable=true)
     * @var Tournament
     */
    private $tournament;

    //     * @ORM\JoinColumn(nullable=false)

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
     * @ORM\Column(type="integer",nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReady = false;

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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserFight", mappedBy="fight")
     * @OrderBy({"isRedCorner" = "DESC"})
     */
    private $usersFight;


    public function getId(): int
    {
        return $this->id;
    }


    public function addUserFight(UserFight $userFight)
    {
//        if ($this->usersFight->contains($userFight))
//        {
//            return;
//        }

        $userFight->setFight($this);

        return $this;
    }

    public function getUsersFight(): Collection
    {
        return $this->usersFight;
    }


    public function getIsReady(): bool
    {
        return $this->isReady;
    }


    public function setIsReady(bool $isReady)
    {
        $this->isReady = $isReady;
    }

    public function toggleReady()
    {
        $this->isReady = $this->isReady ? false : true;
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


    public function getWeight()
    {
        return $this->weight;
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