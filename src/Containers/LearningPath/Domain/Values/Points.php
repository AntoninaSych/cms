<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Values;

use Edhub\Shared\Exceptions\InvalidArgumentException;

final class Points
{
    private $rawPoints;
    const MAX_PINTS_NUMBER = 30;
    const MIN_PINTS_NUMBER = 0;

    private function __construct(string $rawPoints)
    {
        $this->changePoints($rawPoints);
    }

    public static function new(string $rawPoints): Points
    {
        return new self($rawPoints);
    }

    private function changePoints(string $rawPoints)
    {
        $this->validatePoints($rawPoints);
        $this->rawPoints = $rawPoints;
    }

    public function value(): string
    {
        return $this->rawPoints;
    }

    public function validatePoints(string $rawPoints)
    {
        if ($rawPoints > self::MAX_PINTS_NUMBER) {
            throw new InvalidArgumentException('Learning path points are less then ' . self::MAX_PINTS_NUMBER, 422);
        }

        if(self::MIN_PINTS_NUMBER > $rawPoints)
        {
            throw new InvalidArgumentException('Learning path points are more then ' . self::MIN_PINTS_NUMBER, 422);
        }

    }
}