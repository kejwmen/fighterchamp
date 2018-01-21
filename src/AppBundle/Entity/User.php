<?php declare(strict_types = 1);


namespace AppBundle\Entity;

use AppBundle\Entity\Traits\TimestampableTrait;
use AppBundle\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 * @UniqueEntity(fields={"email"}, message="Użytkownik o podanym adresie email jest już zarejestrowany!")
 *
 */
class User implements UserInterface, Serializable
{
    use TimestampableTrait;

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
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $imageName;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return User
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

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
     * @ORM\Column(type="string", unique=true)
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
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean")
     */
    private $male;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Facebook", mappedBy="user")
     */
    private $facebook;

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
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(min="11", max="11")
     * @Assert\NotBlank()
     * @var string
     */
    private $pesel;

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
     * @Assert\Callback
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $type = 1;

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

    public function getRoles()
    {
        return ['ROLE_USER'];
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

    function __call($name, $arguments)
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

    public function getFacebook()
    {
        return $this->facebook;
    }

    public function setFacebook($facebook): void
    {
        $this->facebook = $facebook;
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

    public function getPlec()
    {
       return ($this->getMale()) ? 'M' : 'K';
    }

    public function getAge()
    {
        $referenceDateTimeObject = new \DateTime('now');

        $diff = $referenceDateTimeObject->diff($this->getBirthDay());

        return $diff->y;
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

    public function setUsers(?User $user): void
    {

        if(!$user){
            $this->users->removeElement($this->getCoach());
            return;
        }

        if ($this->users->contains($user)) {
            return;
        }

        $coach = $this->getCoach();

        if($coach){
            $this->users->removeElement($coach);
        }

        $this->users[] = $user;
    }

    public function addUser($user)
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->setUsers($user);
        $this->users->add($user);
    }

    public function removeUser($user)
    {
        $this->users->removeElement($user);
    }


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
}