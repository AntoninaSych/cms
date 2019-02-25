<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Values;

use Edhub\Shared\Exceptions\InvalidArgumentException;

final class Title
{
    private $rawTitle;


    private function __construct(string $rawTitle)
    {
        $this->changeTitle($rawTitle);
    }

    public static function new(string $rawTitle): Title
    {
        return new self($rawTitle);
    }

    private function changeTitle(string $rawTitle)
    {
        $this->validateTitle($rawTitle);
        $this->rawTitle = $rawTitle;
    }

    public function value(): string
    {
        return $this->rawTitle;
    }

    public function __toString(): string
    {
        return (string)$this->rawTitle;
    }


    public function validateTitle(string $rawTitle)
    {
        if (empty(trim($rawTitle)) ) {
            throw new InvalidArgumentException('Learning path title is invalid.', 422);
        }
    }
}