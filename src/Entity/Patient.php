<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    private $middleName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $lastName;

    #[ORM\Column(type: 'string', length: 10)]
    private $gender;

    #[ORM\Column(type: 'date')]
    private $birthDate;

    #[ORM\Column(type: 'datetime')]
    private $registeredAt;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: ConsultationRequest::class)]
    private $consultationRequests;

    #[ORM\OneToOne(inversedBy: 'patient', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $user;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Appointment::class)]
    private $appointments;

    
    public function __construct()
    {
        $this->consultationRequests = new ArrayCollection();
        $this->registeredAt=new \DateTime();
        $this->appointments = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }
   

    public function getFullName(): ?string
    {
        return ucwords($this->__toString()." ".$this->lastName);
  
    }
    public function __toString()
    {
        return ucwords($this->firstName." ".$this->middleName);
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

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

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

    /**
     * @return Collection<int, ConsultationRequest>
     */
    public function getConsultationRequests(): Collection
    {
        return $this->consultationRequests;
    }

    public function addConsultationRequest(ConsultationRequest $consultationRequest): self
    {
        if (!$this->consultationRequests->contains($consultationRequest)) {
            $this->consultationRequests[] = $consultationRequest;
            $consultationRequest->setPatient($this);
        }

        return $this;
    }

    public function removeConsultationRequest(ConsultationRequest $consultationRequest): self
    {
        if ($this->consultationRequests->removeElement($consultationRequest)) {
            // set the owning side to null (unless already changed)
            if ($consultationRequest->getPatient() === $this) {
                $consultationRequest->setPatient(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments[] = $appointment;
            $appointment->setPatient($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getPatient() === $this) {
                $appointment->setPatient(null);
            }
        }

        return $this;
    }

   
}
