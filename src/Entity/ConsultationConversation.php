<?php

namespace App\Entity;

use App\Repository\ConsultationConversationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationConversationRepository::class)]
class ConsultationConversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: ConsultationRequest::class, inversedBy: 'consultationConversations')]
    #[ORM\JoinColumn(nullable: false)]
    private $caseRequest;

    #[ORM\Column(type: 'datetime')]
    private $sentAt;

    #[ORM\Column(type: 'integer')]
    private $type;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $sentBy;

    #[ORM\Column(type: 'text')]
    private $content;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __construct()
    {
    $this->sentAt=new \DateTime();
   
    }
    public function getCaseRequest(): ?ConsultationRequest
    {
        return $this->caseRequest;
    }

    public function setCaseRequest(?ConsultationRequest $caseRequest): self
    {
        $this->caseRequest = $caseRequest;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSentBy(): ?User
    {
        return $this->sentBy;
    }

    public function setSentBy(?User $sentBy): self
    {
        $this->sentBy = $sentBy;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
