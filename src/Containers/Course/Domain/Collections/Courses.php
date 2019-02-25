<?php

namespace Edhub\CMS\Containers\Course\Domain\Collections;

use Edhub\Shared\Assertions\Assertion;
use Doctrine\Common\Collections\ArrayCollection;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;

class Courses extends ArrayCollection
{
    public function __construct(array $items = [])
    {
        Assertion::allIsInstanceOf($items, Course::class);

        parent::__construct($items);
    }

    public static function make(array $courses = []): Courses
    {
        return new static($courses);
    }
}