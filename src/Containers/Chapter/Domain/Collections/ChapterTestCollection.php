<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Collections;

use Edhub\Shared\Assertions\Assertion;
use Doctrine\Common\Collections\ArrayCollection;
use Edhub\CMS\Containers\Chapter\Domain\Entities\ChapterTest;

class ChapterTestCollection extends ArrayCollection
{
    public function __construct(array $elements = [])
    {
        Assertion::allIsInstanceOf($elements, ChapterTest::class);
        parent::__construct($elements);
    }

    public static function make(array $chapters = []): ChapterTestCollection
    {
        return new static($chapters);
    }
}