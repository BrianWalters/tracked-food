<?php


namespace App\DataStructure;


use App\Entity\TrackedFood;
use Symfony\Component\Validator\Constraints\NotBlank;

class LogDto
{
    #[NotBlank]
    public ?TrackedFood $trackedFood = null;

    public ?string $description = '';
}