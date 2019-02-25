<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Values;

final class Subtitle
{
    private $rawSubtitle;


    private function __construct(string $rawSubtitle)
    {
        $this->rawSubtitle = $rawSubtitle;
    }

    public static function new(string $rawSubtitle): Subtitle
    {
        return new self($rawSubtitle);
    }

    public function __toString(): string
    {
        return (string)$this->rawSubtitle;
    }

    public function value(): string
    {
        return $this->rawSubtitle;
    }


}