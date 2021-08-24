<?php

namespace App\Twig;

use App\Domain\TrackedFoodCalculator;
use App\Entity\TrackedFood;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(private TrackedFoodCalculator $trackedFoodCalculator)
    {
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
//            new TwigFilter('lastLog', [$this, 'findLastLog']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isOk', [$this, 'isOk']),
            new TwigFunction('howMuchLonger', [$this, 'howMuchLonger']),
            new TwigFunction('nextAllowedDate', [$this, 'nextAllowedDate'])
        ];
    }

    public function isOk(TrackedFood $trackedFood): bool
    {
        return $this->trackedFoodCalculator
            ->isOkToEat(
                $trackedFood,
                new \DateTime('now'),
                $trackedFood->getLastLog()
            );
    }

    public function howMuchLonger(TrackedFood $trackedFood): ?\DateInterval
    {
        return $this->trackedFoodCalculator
            ->howMuchLonger(
                $trackedFood,
                new \DateTime('now'),
                $trackedFood->getLastLog()
            );
    }

    public function nextAllowedDate(TrackedFood $trackedFood): \DateTime
    {
        return $this->trackedFoodCalculator->getNextAllowedDate(
            $trackedFood->getLastLog(),
            $trackedFood
        );
    }
}
