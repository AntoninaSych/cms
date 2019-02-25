<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\GetChapterTypeList;


use Edhub\CMS\Containers\Chapter\Domain\Values\ChapterType;

class GetChapterTypeListAction
{
    /** @return int[] */
    public function run(): iterable
    {
        return ChapterType::all();
    }
}