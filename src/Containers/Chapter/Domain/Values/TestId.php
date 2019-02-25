<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Values;

final class TestId
{
    /**
     * @var int
     */
    private $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function new(int $id): TestId
    {
        return new self($id);
    }

    public function value(): int
    {
        return $this->id;
    }
}