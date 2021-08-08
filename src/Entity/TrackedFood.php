<?php

namespace App\Entity;

use App\Repository\TrackedFoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TrackedFoodRepository::class)
 */
class TrackedFood
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $timeInterval;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trackedFood")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Log::class, mappedBy="trackedFood", orphanRemoval=true)
     */
    private $logs;

    public function __construct(
        string $description,
        \DateInterval $timeInterval,
        User $user
    ) {
        $this->logs = new ArrayCollection();
        $this->description = $description;
        $this->timeInterval = $timeInterval;
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTimeInterval(): ?\DateInterval
    {
        return $this->timeInterval;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return Collection<array-key, Log>
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function getLastLog(): ?Log
    {
        $collection = $this->logs->matching(
            Criteria::create()
                ->orderBy(['createdAt' => 'DESC'])
                ->setMaxResults(1)
        );

        return $collection->get(0);
    }
}
