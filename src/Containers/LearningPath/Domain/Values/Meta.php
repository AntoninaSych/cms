<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Values;


final class Meta
{
    private $meta;

    private function __construct(array $meta)
    {
        $this->meta = $meta;
    }

    public static function new(array $meta): Meta
    {
        return new self($meta);
    }

    public function value(): array
    {
        return $this->meta;
    }

}