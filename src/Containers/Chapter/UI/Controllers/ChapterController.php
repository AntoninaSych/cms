<?php

namespace Edhub\CMS\Containers\Chapter\UI\Controllers;

use Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapter\CreateChapterAction;
use Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapter\CreateChapterTransporter;
use Edhub\CMS\Containers\Chapter\Application\Actions\DeleteChapter\DeleteChapterAction;
use Edhub\CMS\Containers\Chapter\Application\Actions\DeleteChapter\DeleteChapterTransporter;
use Edhub\CMS\Containers\Chapter\Application\Actions\GetChapter\GetChapterAction;
use Edhub\CMS\Containers\Chapter\Application\Actions\GetChapter\GetChapterTransporter;
use Edhub\CMS\Containers\Chapter\Application\Actions\GetChapterTypeList\GetChapterTypeListAction;
use Edhub\CMS\Containers\Chapter\Application\Actions\UpdateChapter\UpdateChapterAction;
use Edhub\CMS\Containers\Chapter\Application\Actions\UpdateChapter\UpdateChapterTransporter;
use Edhub\CMS\Containers\Chapter\UI\Requests\CreateChapterRequest;
use Edhub\CMS\Containers\Chapter\UI\Requests\UpdateChapterRequest;
use Edhub\CMS\Containers\Chapter\UI\Transformers\ChapterTransformer;
use Edhub\CMS\Containers\Chapter\UI\Transformers\ChapterTypeTransformer;
use Edhub\Shared\UI\BaseApiController;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface as Logger;

class ChapterController extends BaseApiController
{
    public function types(GetChapterTypeListAction $action)
    {
        $types = $action->run();

        return $this->success([
            'types' => $this->transform($types, ChapterTypeTransformer::class)
        ]);
    }

    public function show(int $course, int $chapter, GetChapterAction $action)
    {
        $transporter = new GetChapterTransporter(['id' => $chapter]);
        $chapter = $action->run($transporter);
        $chapterWithTests = $this->transform($chapter, ChapterTransformer::class, ['tests']);
        $chapterWithTests['tests'] = $chapterWithTests['tests']['data'] ?? [];

        return $this->success([
            'chapter' => $chapterWithTests
        ]);
    }

    public function store(int $course, CreateChapterRequest $request, CreateChapterAction $action, Logger $logger)
    {
        $request->merge(compact('course'));
        try {
            /** @var CreateChapterTransporter $transporter */
            $transporter = $request->toTransporter();
            DB::beginTransaction();
            $chapter = $action->run($transporter);
            DB::commit();

            $logger->info(sprintf('Chapter is created for course %d.', $course), ['type' => 'courses.chapters.create.success']);

            return $this->success([
                'chapter' => $this->transform($chapter, ChapterTransformer::class)
            ]);

        } catch (\Throwable $exception) {
            DB::rollBack();
            $logger->error(sprintf('Chapter is not created for course %d.', $course), ['type' => 'courses.chapters.create.failed']);
            throw $exception;
        }
    }

    public function update(int $course, int $chapter, UpdateChapterRequest $request, UpdateChapterAction $action, Logger $logger)
    {
        $request->merge(['id' => $chapter]);
        try {
            /** @var UpdateChapterTransporter $transporter */
            $transporter = $request->toTransporter();

            DB::beginTransaction();
            $chapter = $action->run($transporter);
            DB::commit();

            $logger->info(
                sprintf('Chapter is created for course %d', $chapter->course()->value()),
                ['type' => 'courses.chapters.update.success']
            );

            return $this->success([
                'chapter' => $this->transform($chapter, ChapterTransformer::class, ['tests'])
            ]);
        } catch (\Throwable $exception) {
            DB::rollBack();
            $logger->error('', ['type' => 'courses.chapters.update.failed']);
            throw $exception;
        }
    }

    public function delete(int $course, int $chapterId, DeleteChapterAction $deleteChapter, Logger $logger)
    {
        try {
            DB::beginTransaction();
            $transporter = new DeleteChapterTransporter(['id' => $chapterId]);
            $deleteChapter->run($transporter);
            DB::commit();

            $logger->info(sprintf('Chapter %d is deleted for course %d.', $chapterId, $course), ['type' => 'courses.chapters.delete.success']);

            return $this->success();
        } catch (\Throwable $exception) {
            DB::rollBack();
            $logger->error(
                sprintf('Chapter %d is not deleted for course %d.', $chapterId, $course),
                ['type' => 'courses.chapters.delete.failed', 'reason' => $exception->getMessage()]
            );
            throw $exception;
        }
    }
}