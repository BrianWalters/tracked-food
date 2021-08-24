<?php

namespace App\Tests\Domain;

use App\Domain\TrackedFoodCalculator;
use App\Entity\Log;
use App\Entity\TrackedFood;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TrackedFoodTest extends TestCase
{
    private TrackedFoodCalculator $trackedFoodCalculator;

    protected function setUp(): void
    {
        $this->trackedFoodCalculator = new TrackedFoodCalculator();
    }

    public function makeTestUser(): User
    {
        return new User();
    }

    public function testisOkToEat_nullLog()
    {
        $trackedFood = new TrackedFood(
            'test food',
            \DateInterval::createFromDateString('1 week'),
            $this->makeTestUser()
        );

        $this->assertTrue(
            $this->trackedFoodCalculator->isOkToEat(
                $trackedFood,
                new \DateTime('2021-08-02T00:00:00'),
                null
            )
        );
    }


    public function testIsOkToEat_olderLog()
    {
        $trackedFood = new TrackedFood(
            'test food',
            \DateInterval::createFromDateString('1 week'),
            $this->makeTestUser()
        );

        $aug1Log = new Log(
            $trackedFood
        );
        $aug1Log->setCreatedAt(new \DateTime('2021-08-01T00:00:00'));

        $this->assertFalse(
            $this->trackedFoodCalculator->isOkToEat(
                $trackedFood,
                new \DateTime('2021-08-08T00:00:00'),
                $aug1Log
            )
        );
    }

    public function testIsOkToEat_newLog()
    {
        $trackedFood = new TrackedFood(
            'test food',
            \DateInterval::createFromDateString('1 week'),
            $this->makeTestUser()
        );

        $aug1Log = new Log(
            $trackedFood
        );
        $aug1Log->setCreatedAt(new \DateTime('2021-08-01T00:00:00'));

        $this->assertTrue(
            $this->trackedFoodCalculator->isOkToEat(
                $trackedFood,
                new \DateTime('2021-08-08T00:00:01'),
                $aug1Log
            )
        );
    }

    public function testHowMuchLonger()
    {
        $trackedFood = new TrackedFood(
            'test food',
            \DateInterval::createFromDateString('1 week'),
            $this->makeTestUser()
        );

        $aug1Log = new Log(
            $trackedFood
        );
        $aug1Log->setCreatedAt(new \DateTime('2021-08-01T00:00:00'));

        $actual = $this->trackedFoodCalculator->howMuchLonger(
            $trackedFood,
            new \DateTime('2021-08-07T00:00:00'),
            $aug1Log
        );

        $this->assertEquals(1, $actual->d);
        $this->assertEquals(0, $actual->h);
        $this->assertEquals(0, $actual->m);
    }

    public function testHowMuchLonger_noLong()
    {
        $trackedFood = new TrackedFood(
            'test food',
            \DateInterval::createFromDateString('1 week'),
            $this->makeTestUser()
        );

        $actual = $this->trackedFoodCalculator->howMuchLonger(
            $trackedFood,
            new \DateTime('2021-08-07T00:00:00'),
            null
        );

        $this->assertNull($actual);
    }
}
