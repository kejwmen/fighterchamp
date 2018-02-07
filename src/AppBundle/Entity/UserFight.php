<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Enum\UserFightResult;
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $result;

    public function getResult(): string
    {
        return $this->result;
    }

    public function setResult(UserFightResult $result): void
    {
        $this->result = $result->getValue();
    }

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
}