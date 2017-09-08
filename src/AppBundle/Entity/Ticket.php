<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 9/4/17
 * Time: 11:17 PM
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ticket")
 */
class Ticket
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tournament", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     * @var Tournament
     */
    private $tournament;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $price;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $type;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price)
    {
        $this->price = $price;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function setTournament(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }


}