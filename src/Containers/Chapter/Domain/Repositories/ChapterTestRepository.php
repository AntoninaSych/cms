<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Repositories;

use Edhub\CMS\Containers\Chapter\Domain\Collections\ChapterTestCollection;
use Edhub\CMS\Containers\Chapter\Domain\Entities\ChapterTest;
use Edhub\CMS\Containers\Chapter\Domain\Exception\ChapterTestNotFound;
use Edhub\CMS\Containers\Chapter\Domain\Values\TestId;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ChapterTestRepository
{
    public function save(ChapterTest $chapterTest): void
    {
        $chapterTest->saveOrFail();
    }

    public function remove(ChapterTest $chapterTest): void
    {
        $chapterTest->delete();
    }

    public function getOne(TestId $testId): ChapterTest
    {
        try {
            return ChapterTest::query()->findOrFail($testId->value());
        } catch (ModelNotFoundException $exception) {
            throw new ChapterTestNotFound();
        }
    }

    public function findByIds(TestId ...$ids): ChapterTestCollection
    {
        if (empty($ids)) {
            return ChapterTestCollection::make();
        }

        $rawIds = array_map(function (TestId $testId) {
            return $testId->value();
        }, $ids);

        $implodedRawIds = implode(',', $rawIds);
        $chapterTests = ChapterTest::query()
            ->whereIn('id', $rawIds)
            ->orderByRaw("FIELD(id, $implodedRawIds)")
            ->get()
            ->all();
        
        return ChapterTestCollection::make($chapterTests);
    }



}