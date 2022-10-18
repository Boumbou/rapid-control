<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ShortListRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ShortListRepository::class)
 */
class ShortList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("shortlist:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("shortlist:read")
     */
    private $centerId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="shortLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCenterId(): ?string
    {
        return $this->centerId;
    }

    public function setCenterId(string $centerId): self
    {
        $this->centerId = $centerId;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
