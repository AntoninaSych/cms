<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Values;

final class Code
{
    private $rawCode;


    private function __construct(string $rawCode)
    {
        $this->rawCode = $rawCode;
    }

    public function __toString(): string
    {
        return (string)$this->rawCode;
    }

    public static function new(string $rawCode): Code
    {
        return new self($rawCode);
    }

    public function value(): string
    {
        return $this->rawCode;
    }

}