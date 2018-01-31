<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_fight")
 */
class UserFight
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="userFights")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Fight", inversedBy="usersFight", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fight;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $isRedCorner = false;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $isDisqualified = false;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $isWinner = false;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $isDraw = false;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getFight(): Fight
    {
        return $this->fight;
    }

    public function setFight(Fight $fight): void
    {
        $this->fight = $fight;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function isRedCorner(): ?bool
    {
        return $this->isRedCorner;
    }

    public function isDisqualified(): bool
    {
        return $this->isDisqualified;
    }

    public function setIsDisqualified(bool $isDisqualified): void
    {
        $this->isDisqualified = $isDisqualified;
    }

    public function isWinner(): bool
    {
        return $this->isWinner;
    }

    public function setIsWinner(bool $isWinner): void
    {
        $this->isWinner = $isWinner;
    }

    public function isDraw(): bool
    {
        return $this->isDraw;
    }

    public function setIsDraw(bool $isDraw): void
    {
        $this->isDraw = $isDraw;
    }
}