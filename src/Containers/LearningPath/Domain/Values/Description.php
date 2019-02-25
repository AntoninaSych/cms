<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Values;

final class Description
{
    private $rawDescription;

    private function __construct(array $rawDescription)
    {
        $this->rawDescription = $rawDescription;
    }

    public static function new(array $rawDescription): Description
    {
        return new self($rawDescription);
    }

    public function value(): array
    {
        return $this->rawDescription;
    }
}