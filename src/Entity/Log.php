<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=LogRepository::class)
 */
class Log
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=TrackedFood::class, inversedBy="logs")
     * @ORM\JoinColumn(nullable=false)
     */
    private TrackedFood $trackedFood;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $eatenAt;

    public function __construct(TrackedFood $trackedFood)
    {
        $this->trackedFood = $trackedFood;
        $this->eatenAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrackedFood(): ?TrackedFood
    {
        return $this->trackedFood;
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

    public function getEatenAt(): ?\DateTimeInterface
    {
        return $this->eatenAt;
    }

    public function setEatenAt(?\DateTimeInterface $eatenAt): self
    {
        $this->eatenAt = $eatenAt;

        return $this;
    }
}
