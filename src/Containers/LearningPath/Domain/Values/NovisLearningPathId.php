<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Values;

final class NovisLearningPathId
{
    private $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function new(int $id): NovisLearningPathId
    {
        return new self($id);
    }

    public function value(): int
    {
        return $this->id;
    }
}