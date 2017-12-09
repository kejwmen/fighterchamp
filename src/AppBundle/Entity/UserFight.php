<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 12/9/17
 * Time: 12:39 AM
 */

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="fights")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Fight", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fight;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @var bool
     */
    private $isRedCorner;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getFight()
    {
        return $this->fight;
    }

    public function setFight(Fight $fight): void
    {
        $this->fight = $fight;
    }

    public function isRedCorner(): ?bool
    {
        return $this->isRedCorner;
    }


    public function setIsRedCorner(?bool $isRedCorner): void
    {
        $this->isRedCorner = $isRedCorner;
    }

}