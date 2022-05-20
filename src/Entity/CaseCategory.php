<?php

namespace App\Entity;

use App\Repository\CaseCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaseCategoryRepository::class)]
class CaseCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\OneToMany(mappedBy: 'caseCategory', targetEntity: ConsultationRequest::class)]
    private $consultationRequests;

    public function __construct()
    {
        $this->consultationRequests = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->name ;
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
}
