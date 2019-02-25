<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Values;

final class LearningPathId
{
    private $rawId;


    private function __construct(int $rawId)
    {
        $this->changeId($rawId);
    }

    public static function new(int $rawId): LearningPathId
    {
        return new self($rawId);
    }

    private function changeId(int $rawId)
    {
        $this->rawId = $rawId;
    }

    public function value(): int
    {
        return $this->rawId;
    }

}