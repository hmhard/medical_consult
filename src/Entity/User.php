<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['phone'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;
    #[ORM\Column(type: 'string', length: 255)]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    private $middleName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $lastName;

    #[ORM\Column(type: 'string', length: 10)]
    private $gender;

    #[ORM\ManyToOne(targetEntity: UserType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $userType;

    #[ORM\Column(type: 'boolean')]
    private $isActive;

    #[ORM\Column(type: 'string', length: 20, unique: true)]
    #[Assert\Regex( pattern:'/^(\+2519|09)[0-9]{8}$/',   message:'the phone number  is not valid.')]
    private $phone;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private $registeredBy;

    #[ORM\Column(type: 'datetime')]
    private $registeredAt;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Patient::class, cascade: ['persist', 'remove'])]
    private $patient;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Doctor::class, cascade: ['persist', 'remove'])]
    private $doctor;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function __construct()
    {
        $this->registeredAt=new \DateTime();
        $this->isActive=1;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    public function getFullName(): ?string
    {
        return $this->__toString()." ".$this->lastName;
  
    }
    public function __toString()
    {
        return ucwords($this->firstName." ".$this->middleName);
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getUserType(): ?UserType
    {
        return $this->userType;
    }

    public function setUserType(?UserType $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getRegisteredBy(): ?self
    {
        return $this->registeredBy;
    }

    public function setRegisteredBy(?self $registeredBy): self
    {
        $this->registeredBy = $registeredBy;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        // unset the owning side of the relation if necessary
        if ($patient === null && $this->patient !== null) {
            $this->patient->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($patient !== null && $patient->getUser() !== $this) {
            $patient->setUser($this);
        }

        $this->patient = $patient;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }
    public function setDoctor(?Doctor $doctor): self
    {
        // unset the owning side of the relation if necessary
        if ($doctor === null && $this->doctor !== null) {
            $this->doctor->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($doctor !== null && $doctor->getUser() !== $this) {
            $doctor->setUser($this);
        }

        $this->doctor = $doctor;

        return $this;
    }
}
