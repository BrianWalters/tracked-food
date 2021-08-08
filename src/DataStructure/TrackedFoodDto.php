<?php


namespace App\DataStructure;


use Symfony\Component\Validator\Constraints as Assert;

class TrackedFoodDto
{
    #[Assert\NotBlank]
    public ?string $description = null;

    #[Assert\NotBlank]
    public int $timeIntervalCount = 1;

    #[Assert\NotBlank]
    public string $timeIntervalType = 'week';
}