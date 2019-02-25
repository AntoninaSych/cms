<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Collections;

use Edhub\CMS\Containers\Chapter\Domain\Entities\ChapterTest;

class OrderedChapterTestCollection extends ChapterTestCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct($elements);
        $position = 1;
        /** @var ChapterTest $chapterTest */
        foreach ($this->getValues() as $chapterTest) {
            $chapterTest->changePosition($position);
            $position++;
        }
    }

}