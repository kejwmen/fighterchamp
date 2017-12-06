<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 16.07.16
 * Time: 21:09
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SignUpTournamentRepository")
 * @ORM\Table(name="signuptournament")
 */
class SignUpTournament
{

    public function __construct(User $user, Tournament $tournament)
    {
        $this->user = $user;
        $this->tournament = $tournament;
        $this->created_at = new \DateTime('now');
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $weight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $weighted;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $formula;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tournament", inversedBy="signUpTournament")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="signUpTournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isReady = false;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isPaid = false;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;


    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(
     *      min = 1,
     *      max = 100
     * )
     */
    private $trainingTime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $youtubeId = '';


    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $musicArtistAndTitle = '';


    public function getTrainingTime()
    {
        return $this->trainingTime;
    }


    public function setTrainingTime($trainingTime)
    {
        $this->trainingTime = $trainingTime;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }


    public function delete()
    {
        $this->deleted_at = new \DateTime('now');
    }




    /**
     * @return mixed
     */
    public function getIsPaid()
    {
        return $this->isPaid;
    }

    /**
     * @param mixed $isPaid
     */
    public function setIsPaid($isPaid)
    {
        $this->isPaid = $isPaid;
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


    public function getWeight(): ?string
    {
        return $this->weight;
    }


    public function setWeight($weight)
    {
        $this->weight = $weight;
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

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function howOldUserIs()
    {
        $birthDay = $this->user->getBirthDay();
        $tournamentDay = $this->tournament->getStart();

        $date_diff =  date_diff($birthDay, $tournamentDay);

        return $date_diff->format("%y");
    }

    public function getStazTreningowy()
    {
        if($this->getTrainingTime()){
            return '(staż ' . $this->getTrainingTime() . "miesiące) ";
        }
        return null;
    }


    public function getWeighted()
    {
        return $this->weighted;
    }

    public function setWeighted($weighted)
    {
        $this->weighted = $weighted;
    }

    public function getFinallWeight()
    {
        return $this->weighted ?? $this->weight;
    }


    public function getYoutubeId(): ?string
    {
        return $this->youtubeId;
    }

    public function setYoutubeId(?string $youtubeId = ''): void
    {
        $this->youtubeId = $youtubeId;
    }

    public function getMusicArtistAndTitle(): ?string
    {
        return $this->musicArtistAndTitle;
    }

    public function setMusicArtistAndTitle(?string $musicArtistAndTitle = '')
    {
        $this->musicArtistAndTitle = $musicArtistAndTitle;
    }




    public function __toString()
    {
        return (string) $this->user->getPlec() . "  ". $this->user->getSurname() . " " . $this->user->getName() .
             " " . $this->getFormula() . " " . $this->getFinallWeight() . "kg" .
            " " . $this->getStazTreningowy() . $this->howOldUserIs() . 'lat ' . $this->user->getClub();
    }




}