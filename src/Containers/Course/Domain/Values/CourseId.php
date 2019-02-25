<?php

namespace Edhub\CMS\Containers\Course\Domain\Values;

use Edhub\Shared\Assertions\Assertion;

class CourseId
{
    private $id;

    private function __construct(int $id)
    {
        Assertion::notEmpty($id, 'Course ID is empty.');

        $this->id = $id;
    }

    public static function new(int $id): CourseId
    {
        return new self($id);
    }

    public function value(): int
    {
        return $this->id;
    }
}
