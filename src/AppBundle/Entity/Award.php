<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 4/27/18
 * Time: 1:05 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="award")
 */
class Award
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var UserFight
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserFight", inversedBy="awards")
     */
    private $userFight;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $description;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserFight(): UserFight
    {
        return $this->userFight;
    }

    public function setUserFight(UserFight $userFight): void
    {
        $this->userFight = $userFight;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}