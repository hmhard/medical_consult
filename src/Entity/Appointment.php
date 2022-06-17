<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $appointmentDate;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private $patient;

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private $doctor;

    #[ORM\Column(type: 'boolean')]
    private $isMade;

    #[ORM\ManyToOne(targetEntity: ConsultationRequest::class, inversedBy: 'appointments')]
    private $appointmentCase;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'boolean')]
    private $isApproved=0;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $approvedAt;

    #[ORM\Column(type: 'integer')]
    private $status=0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppointmentDate(): ?\DateTimeInterface
    {
        return $this->appointmentDate;
    }

    public function setAppointmentDate(\DateTimeInterface $appointmentDate): self
    {
        $this->appointmentDate = $appointmentDate;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function isIsMade(): ?bool
    {
        return $this->isMade;
    }

    public function setIsMade(bool $isMade): self
    {
        $this->isMade = $isMade;

        return $this;
    }

    public function getAppointmentCase(): ?ConsultationRequest
    {
        return $this->appointmentCase;
    }

    public function setAppointmentCase(?ConsultationRequest $appointmentCase): self
    {
        $this->appointmentCase = $appointmentCase;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    public function getApprovedAt(): ?\DateTimeInterface
    {
        return $this->approvedAt;
    }

    public function setApprovedAt(?\DateTimeInterface $approvedAt): self
    {
        $this->approvedAt = $approvedAt;

        return $this;
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
}
