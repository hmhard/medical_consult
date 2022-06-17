<?php

namespace App\Entity;

use App\Repository\ConsultationRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConsultationRequestRepository::class)]
class ConsultationRequest
{
   const STATUS_TEXT=[
        0=>"Just Requested",
        1=>"Active",
        2=>"Solved",
        3=>"Rejected",
        4=>"Closed",
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'consultationRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private $patient;

    #[ORM\Column(type: 'text')]
     
     #[Assert\Length(
        min: 20,
         minMessage: "Request description must be at least {{ limit }} characters long"
    )]
    
    private $requestDescription;

    #[ORM\ManyToOne(targetEntity: CaseCategory::class, inversedBy: 'consultationRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private $caseCategory;

    #[ORM\Column(type: 'datetime')]
    private $requestedAt;

    #[ORM\Column(type: 'integer')]
    private $status;

    #[ORM\OneToMany(mappedBy: 'caseRequest', targetEntity: ConsultationConversation::class)]
    private $consultationConversations;

    #[ORM\ManyToOne(targetEntity: Doctor::class, inversedBy: 'consultationRequests')]
    private $assignedTo;

    #[ORM\OneToMany(mappedBy: 'appointmentCase', targetEntity: Appointment::class)]
    private $appointments;

    #[ORM\Column(type: 'string', length: 255)]
    private $rfn;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __construct()
    {
    $this->status=0;     
    $this->requestedAt=new \DateTime();
    $this->consultationConversations = new ArrayCollection();
    $this->appointments = new ArrayCollection();     
   
    }
    public function __toString(){
        return substr($this->requestDescription,0,30)."...";
    }
    public function getStatusText()
    {
        return self::STATUS_TEXT[$this->status];
    }
    public function getIsToday()
    {
        if($this->appointments->count()){
            return $this->appointments->last()->getAppointmentDate()->format('Y-m-d')==(new \Datetime())->format('Y-m-d');
       
        }
        return false;
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

    public function getRequestDescription(): ?string
    {
        return $this->requestDescription;
    }

    public function setRequestDescription(string $requestDescription): self
    {
        $this->requestDescription = $requestDescription;

        return $this;
    }

    public function getCaseCategory(): ?CaseCategory
    {
        return $this->caseCategory;
    }

    public function setCaseCategory(?CaseCategory $caseCategory): self
    {
        $this->caseCategory = $caseCategory;

        return $this;
    }

    public function getRequestedAt(): ?\DateTimeInterface
    {
        return $this->requestedAt;
    }

    public function setRequestedAt(\DateTimeInterface $requestedAt): self
    {
        $this->requestedAt = $requestedAt;

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

    /**
     * @return Collection<int, ConsultationConversation>
     */
    public function getConsultationConversations(): Collection
    {
        return $this->consultationConversations;
    }

    public function addConsultationConversation(ConsultationConversation $consultationConversation): self
    {
        if (!$this->consultationConversations->contains($consultationConversation)) {
            $this->consultationConversations[] = $consultationConversation;
            $consultationConversation->setCaseRequest($this);
        }

        return $this;
    }

    public function removeConsultationConversation(ConsultationConversation $consultationConversation): self
    {
        if ($this->consultationConversations->removeElement($consultationConversation)) {
            // set the owning side to null (unless already changed)
            if ($consultationConversation->getCaseRequest() === $this) {
                $consultationConversation->setCaseRequest(null);
            }
        }

        return $this;
    }

    public function getAssignedTo(): ?Doctor
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?Doctor $assignedTo): self
    {
        $this->assignedTo = $assignedTo;

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
            $appointment->setAppointmentCase($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getAppointmentCase() === $this) {
                $appointment->setAppointmentCase(null);
            }
        }

        return $this;
    }

    public function getRfn(): ?string
    {
        return $this->rfn;
    }

    public function setRfn(string $rfn): self
    {
        $this->rfn = $rfn;

        return $this;
    }
}
