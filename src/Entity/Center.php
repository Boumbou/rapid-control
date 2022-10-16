<?php

namespace App\Entity;

use App\Repository\CenterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CenterRepository::class)
 */
class Center
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $minimalPrice;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $maximumPrice;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $priceChangeDate;

    /**
     * @ORM\Column(type="bigint")
     */
    private $incNumber;

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

    public function getMinimalPrice(): ?float
    {
        return $this->minimalPrice;
    }

    public function setMinimalPrice(?float $minimalPrice): self
    {
        $this->minimalPrice = $minimalPrice;

        return $this;
    }

    public function getMaximumPrice(): ?float
    {
        return $this->maximumPrice;
    }

    public function setMaximumPrice(?float $maximumPrice): self
    {
        $this->maximumPrice = $maximumPrice;

        return $this;
    }

    public function getPriceChangeDate(): ?\DateTimeInterface
    {
        return $this->priceChangeDate;
    }

    public function setPriceChangeDate(?\DateTimeInterface $priceChangeDate): self
    {
        $this->priceChangeDate = $priceChangeDate;

        return $this;
    }

    public function getIncNumber(): ?string
    {
        return $this->incNumber;
    }

    public function setIncNumber(string $incNumber): self
    {
        $this->incNumber = $incNumber;

        return $this;
    }
}
