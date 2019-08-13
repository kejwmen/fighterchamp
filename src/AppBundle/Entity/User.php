<?php declare(strict_types = 1);

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\TimestampableTrait;
use AppBundle\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"email"}, message="Użytkownik o podanym adresie email jest już zarejestrowany!")
 *
 */
class User implements UserInterface, Serializable
{
    use TimestampableTrait;

    public const TYPE_FIGHTER = 1;
    public const TYPE_COACH = 2;
    public const TYPE_FAN = 3;

    public function __construct()
    {
        $this->signUpTournaments = new ArrayCollection();
        $this->tournamentAdmin = new ArrayCollection();
        $this->userFights = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $imageName;

    /**
     * @param string $imageName
     *
     * @return User
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function removeFile()
    {
        $this->imageName = null;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            ) = unserialize($serialized);
    }



    /**
     * @ORM\ManyToMany(targetEntity="User")
     **/
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserTask", mappedBy="user")
     */
    private $userTasks;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $surname;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthDay;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Club", inversedBy="users")
     */
    private $club;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $male = true;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @Assert\NotBlank(groups={"Registration"})
     */
    private $plain_password;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SignUpTournament", mappedBy="user")
     */
    private $signUpTournaments;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $type = 1;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $fatherName;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $motherName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $pesel;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $valid = false;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $hash;

    /**
     * @var array
     * @ORM\Column(type="simple_array")
     */
    private $roles = ['ROLE_USER'];

    public function isFighter(): bool
    {
        return self::TYPE_FIGHTER === 1;
    }

    public function isCoach(): bool
    {
        return self::TYPE_FIGHTER === 2;
    }

    public function isFan(): bool
    {
        return self::TYPE_FIGHTER === 3;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function validate(): void
    {
        $this->valid = true;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getPesel(): ?string
    {
        return $this->pesel;
    }

    public function setPesel(?string $pesel): void
    {
        $this->pesel = $pesel;
    }

    public function getFatherName(): ?string
    {
        return $this->fatherName;
    }

    public function getMotherName(): ?string
    {
        return $this->motherName;
    }

    public function setFatherName(?string $fatherName): void
    {
        $this->fatherName = $fatherName;
    }

    public function setMotherName(?string $motherName): void
    {
        $this->motherName = $motherName;
    }



//    /**
//     * @var UserInsuranceData
//     * @ORM\OneToOne(targetEntity="AppBundle\Entity\UserInsuranceData")
//     */
//    private $insuranceData;


    public function updateInsuranceData(UserInsuranceData $insuranceData)
    {
        $this->insuranceData = $insuranceData;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        $this->plain_password = null;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }


    public function getEmail()
    {
        return $this->email;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }


    public function getSurname()
    {
        return $this->surname;
    }


    public function setSurname($surname)
    {
        $this->surname = $surname;
    }


    public function getBirthDay()
    {
        return $this->birthDay;
    }


    public function setBirthDay(DateTime $birthDay)
    {
        $this->birthDay = $birthDay;
    }


    public function getClub()
    {
        return $this->club;
    }


    public function setClub($club)
    {
        $this->club = $club;
    }


    public function getPhone()
    {
        return $this->phone;
    }


    public function setPhone($phone)
    {
        $this->phone = $phone;
    }


    public function getMale()
    {
        return $this->male;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPlainPassword()
    {
        return $this->plain_password;
    }

    public function setPlainPassword($plain_password)
    {
        $this->plain_password = $plain_password;
        $this->password = null;
    }

    public function getSignUpTournaments()
    {
        return $this->signUpTournaments;
    }

    public function getSignUpTournament($tournament)
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('tournament', $tournament));
        $criteria->andWhere(Criteria::expr()->eq('deleted_at', null));
        $criteria->getFirstResult();

        return $this->signUpTournaments->matching($criteria)->first();
    }

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserFight", mappedBy="user")
     */
    private $userFights;

    /**
     * @ORM\OneToMany(targetEntity="UserAdminTournament", mappedBy="user")
     */
    private $tournamentAdmin;

    public function getTournamentAdmin()
    {
        return $this->tournamentAdmin;
    }

    public function getUserFights()
    {
        return $this->userFights;
    }

    public function __toString()
    {
        return $this->name .' '. $this->surname;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers(?User $user)
    {
        if(!$user){
            if($this->getCoach()){
                $this->removeUser($this->getCoach());
            }
            return;
        }


        if($coach = $this->getCoach()){
            $this->removeUser($coach);
        }

        $this->addUser($user);
    }

    public function addUser(?User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addUser($this);
        }
    }

    public function removeUser(User $user)
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeUser($this);
        }
    }

    /**
     * @return User|false
     */
    public function getCoach()
    {
        return $this->users->matching(UserRepository::createCoachCriteria())->first();
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setMale($male): void
    {
        $this->male = $male;
    }

    public function getFights() : Collection
    {

        $fights = new ArrayCollection();

        foreach ($this->userFights as $userFight)
        {
            if($userFight->getFight()->getIsVisible()){

                $fights[]= $userFight->getFight();
            }

        }

        return $fights;
    }
}