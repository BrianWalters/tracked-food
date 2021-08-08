<?php


namespace App\Domain;


use App\Entity\Log;
use App\Entity\TrackedFood;

class TrackedFoodCalculator
{

    public function isOkToEat(TrackedFood $trackedFood, \DateTime $date, ?Log $log)
    {
        if (!$log)
            return true;

        $nextAllowedDate = (clone $log->getCreatedAt())->add(
            $trackedFood->getTimeInterval()
        );

        return $date > $nextAllowedDate;
    }
}