<?php

namespace App\Entity;

use App\Repository\PaymentFeeRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PaymentFeeRepository::class)]
#[UniqueEntity(fields: ['category'], message: 'this category Already Exists! please update if you want')]

class PaymentFee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'paymentFee', targetEntity: CaseCategory::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'float')]
    private $taxRate;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?CaseCategory
    {
        return $this->category;
    }

    public function setCategory(CaseCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTaxRate(): ?float
    {
        return $this->taxRate;
    }

    public function setTaxRate(float $taxRate): self
    {
        $this->taxRate = $taxRate;

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
}
