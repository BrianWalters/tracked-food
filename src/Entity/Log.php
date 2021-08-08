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
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TrackedFood::class, inversedBy="logs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trackedFood;

    public function __construct(TrackedFood $trackedFood)
    {
        $this->trackedFood = $trackedFood;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrackedFood(): ?TrackedFood
    {
        return $this->trackedFood;
    }
}
