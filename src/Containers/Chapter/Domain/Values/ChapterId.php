<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Values;


final class ChapterId
{
    /**
     * @var int
     */
    private $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function new(int $id): ChapterId
    {
        return new self($id);
    }

    public function value(): int
    {
        return $this->id;
    }
}