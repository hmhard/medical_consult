<?php

namespace App\Entity;

use App\Repository\CaseCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CaseCategoryRepository::class)]
class CaseCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(
        min : 4, 
        minMessage : "this field must be at least {{ limit }} characters long",
    )]
  
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
           min : 15, 
           minMessage : "this field must be at least {{ limit }} characters long",
       )]
     
    private $description;

    #[ORM\OneToMany(mappedBy: 'caseCategory', targetEntity: ConsultationRequest::class)]
    private $consultationRequests;

    #[ORM\OneToMany(mappedBy: 'specialization', targetEntity: Doctor::class)]
    private $doctors;

    #[ORM\OneToOne(mappedBy: 'category', targetEntity: PaymentFee::class, cascade: ['persist', 'remove'])]
    private $paymentFee;

    public function __construct()
    {
        $this->consultationRequests = new ArrayCollection();
        $this->doctors = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $consultationRequest->setCaseCategory($this);
        }

        return $this;
    }

    public function removeConsultationRequest(ConsultationRequest $consultationRequest): self
    {
        if ($this->consultationRequests->removeElement($consultationRequest)) {
            // set the owning side to null (unless already changed)
            if ($consultationRequest->getCaseCategory() === $this) {
                $consultationRequest->setCaseCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Doctor>
     */
    public function getDoctors(): Collection
    {
        return $this->doctors;
    }

    public function addDoctor(Doctor $doctor): self
    {
        if (!$this->doctors->contains($doctor)) {
            $this->doctors[] = $doctor;
            $doctor->setSpecialization($this);
        }

        return $this;
    }

    public function removeDoctor(Doctor $doctor): self
    {
        if ($this->doctors->removeElement($doctor)) {
            // set the owning side to null (unless already changed)
            if ($doctor->getSpecialization() === $this) {
                $doctor->setSpecialization(null);
            }
        }

        return $this;
    }

    public function getPaymentFee(): ?PaymentFee
    {
        return $this->paymentFee;
    }

    public function setPaymentFee(PaymentFee $paymentFee): self
    {
        // set the owning side of the relation if necessary
        if ($paymentFee->getCategory() !== $this) {
            $paymentFee->setCategory($this);
        }

        $this->paymentFee = $paymentFee;

        return $this;
    }
}
