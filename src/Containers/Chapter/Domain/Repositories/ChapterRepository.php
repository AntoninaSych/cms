<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Repositories;

use Edhub\CMS\Containers\Chapter\Domain\Entities\Chapter;
use Edhub\CMS\Containers\Chapter\Domain\Exception\ChapterNotFound;
use Edhub\CMS\Containers\Chapter\Domain\Values\ChapterId;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ChapterRepository
{
    /**
     * @param ChapterId $id
     * @return Chapter
     * @throws ChapterNotFound
     */
    public function getOne(ChapterId $id): Chapter
    {
        try {
            return Chapter::query()->findOrFail($id->value());
        } catch (ModelNotFoundException $exception) {
            throw new ChapterNotFound();
        }
    }

    public function remove(Chapter $chapter): void
    {
        $chapter->delete();
    }

    public function save(Chapter $chapter): void
    {
        $chapter->saveOrFail();
    }
}