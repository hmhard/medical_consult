<?php

namespace App\Entity;

use App\Repository\DoctorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: DoctorRepository::class)]
#[UniqueEntity(fields: ['user'], message: 'this Doctor Already Assigned! please update if you want')]

class Doctor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $status;

  

    #[ORM\OneToMany(mappedBy: 'assignedTo', targetEntity: ConsultationRequest::class)]
    private $consultationRequests;

    #[ORM\OneToOne(inversedBy: 'doctor',targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToMany(mappedBy: 'doctor', targetEntity: Appointment::class)]
    private $appointments;

    #[ORM\ManyToOne(targetEntity: CaseCategory::class, inversedBy: 'doctors')]
    #[ORM\JoinColumn(nullable: false)]
    private $specialization;

    #[ORM\Column(type: 'boolean')]
    private $monday=0;

    #[ORM\Column(type: 'boolean')]
    private $tuesday=0;

    #[ORM\Column(type: 'boolean')]
    private $wednesday=0;

    #[ORM\Column(type: 'boolean')]
    private $thursday=0;

    #[ORM\Column(type: 'boolean')]
    private $friday=0;

    #[ORM\Column(type: 'boolean')]
    private $saturday=0;

    #[ORM\Column(type: 'boolean')]
    private $sunday=0;

    #[ORM\Column(type: 'time')]
    private $availableTimeFrom ;

    #[ORM\Column(type: 'time')]
    private $availableTimeTo;

   

    public function getAllFields() {
        return get_object_vars($this);
    }

    public function __construct()
    {
        $this->consultationRequests = new ArrayCollection();
        $this->status =1;
        $this->availableTimeFrom = new \DateTime();
        $this->availableTimeTo = new \DateTime();
        $this->appointments = new ArrayCollection();
    }
    public function __toString()
    {
        
   return "Dr. ".$this->user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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
            $consultationRequest->setAssignedTo($this);
        }

        return $this;
    }

    public function removeConsultationRequest(ConsultationRequest $consultationRequest): self
    {
        if ($this->consultationRequests->removeElement($consultationRequest)) {
            // set the owning side to null (unless already changed)
            if ($consultationRequest->getAssignedTo() === $this) {
                $consultationRequest->setAssignedTo(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
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
            $appointment->setDoctor($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getDoctor() === $this) {
                $appointment->setDoctor(null);
            }
        }

        return $this;
    }

    public function getSpecialization(): ?CaseCategory
    {
        return $this->specialization;
    }

    public function setSpecialization(?CaseCategory $specialization): self
    {
        $this->specialization = $specialization;

        return $this;
    }

    public function isMonday(): ?bool
    {
        return $this->monday;
    }

    public function setMonday(bool $monday): self
    {
        $this->monday = $monday;

        return $this;
    }

    public function isTuesday(): ?bool
    {
        return $this->tuesday;
    }

    public function setTuesday(bool $tuesday): self
    {
        $this->tuesday = $tuesday;

        return $this;
    }

    public function isWednesday(): ?bool
    {
        return $this->wednesday;
    }

    public function setWednesday(bool $wednesday): self
    {
        $this->wednesday = $wednesday;

        return $this;
    }

    public function isThursday(): ?bool
    {
        return $this->thursday;
    }

    public function setThursday(bool $thursday): self
    {
        $this->thursday = $thursday;

        return $this;
    }

    public function isFriday(): ?bool
    {
        return $this->friday;
    }

    public function setFriday(bool $friday): self
    {
        $this->friday = $friday;

        return $this;
    }

    public function isSaturday(): ?bool
    {
        return $this->saturday;
    }

    public function setSaturday(bool $saturday): self
    {
        $this->saturday = $saturday;

        return $this;
    }

    public function isSunday(): ?bool
    {
        return $this->sunday;
    }

    public function setSunday(bool $sunday): self
    {
        $this->sunday = $sunday;

        return $this;
    }

    public function getAvailableTimeFrom(): ?\DateTimeInterface
    {
        return $this->availableTimeFrom;
    }

    public function setAvailableTimeFrom(\DateTimeInterface $availableTimeFrom): self
    {
        $this->availableTimeFrom = $availableTimeFrom;

        return $this;
    }

    public function getAvailableTimeTo(): ?\DateTimeInterface
    {
        return $this->availableTimeTo;
    }

    public function setAvailableTimeTo(\DateTimeInterface $availableTimeTo): self
    {
        $this->availableTimeTo = $availableTimeTo;

        return $this;
    }
}
