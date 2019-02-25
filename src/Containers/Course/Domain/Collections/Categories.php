<?php

namespace Edhub\CMS\Containers\Course\Domain\Collections;

use Edhub\Shared\Assertions\Assertion;
use Doctrine\Common\Collections\ArrayCollection;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategory;

class Categories extends ArrayCollection
{
    public function __construct(array $items = [])
    {
        Assertion::allIsInstanceOf($items, CourseCategory::class);

        parent::__construct($items);
    }
}