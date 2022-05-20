<?php

namespace App\Entity;

use App\Repository\ConsultationRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRequestRepository::class)]
class ConsultationRequest
{
   const STATUS_TEXT=[
        0=>"Just Requested",
        1=>"Active",
        2=>"Solved",
        3=>"Rejected",
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'consultationRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private $patient;

    #[ORM\Column(type: 'text')]
    private $requestDescription;

    #[ORM\ManyToOne(targetEntity: CaseCategory::class, inversedBy: 'consultationRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private $caseCategory;

    #[ORM\Column(type: 'datetime')]
    private $requestedAt;

    #[ORM\Column(type: 'integer')]
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getStatusText()
    {
        return self::STATUS_TEXT[$this->status];
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
}
