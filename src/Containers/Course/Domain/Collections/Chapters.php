<?php

namespace Edhub\CMS\Containers\Course\Domain\Collections;

use Edhub\Shared\Assertions\Assertion;
use Doctrine\Common\Collections\ArrayCollection;
use Edhub\CMS\Containers\Chapter\Domain\Entities\Chapter;

class Chapters extends ArrayCollection
{
    public function __construct(array $elements = [])
    {
        Assertion::allIsInstanceOf($elements, Chapter::class);

        parent::__construct($elements);
    }

    public static function make(array $chapters = []): Chapters
    {
        return new self($chapters);
    }
}