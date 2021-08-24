<?php


namespace App\Domain;


use App\Entity\Log;
use App\Entity\TrackedFood;

class TrackedFoodCalculator
{

    public function isOkToEat(TrackedFood $trackedFood, \DateTime $date, ?Log $log): bool
    {
        if (!$log)
            return true;

        $nextAllowedDate = $this->getNextAllowedDate($log, $trackedFood);

        return $date > $nextAllowedDate;
    }

    public function howMuchLonger(TrackedFood $trackedFood, \DateTime $date, ?Log $log): ?\DateInterval
    {
        if (!$log)
            return null;

        $nextAllowedDate = $this->getNextAllowedDate($log, $trackedFood);

        return $date->diff($nextAllowedDate);
    }

    public function getNextAllowedDate(Log $log, TrackedFood $trackedFood): \DateTime
    {
        return (clone $log->getCreatedAt())->add(
            $trackedFood->getTimeInterval()
        );
    }
}