<?php

namespace Edhub\CMS\Containers\Chapter\UI\Controllers;

use Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapterTest\CreateChapterTestAction;
use Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapterTest\CreateChapterTestTransporter;
use Edhub\CMS\Containers\Chapter\Application\Actions\DeleteChapterTest\DeleteChapterTestAction;
use Edhub\CMS\Containers\Chapter\Application\Actions\DeleteChapterTest\DeleteChapterTestTransporter;
use Edhub\CMS\Containers\Chapter\Application\Actions\GetChapterTest\GetChapterTestAction;
use Edhub\CMS\Containers\Chapter\Application\Actions\GetChapterTest\GetChapterTestTransporter;
use Edhub\CMS\Containers\Chapter\Application\Actions\UpdateChapterTest\UpdateChapterTestAction;
use Edhub\CMS\Containers\Chapter\Application\Actions\UpdateChapterTest\UpdateChapterTestTransporter;
use Edhub\CMS\Containers\Chapter\UI\Requests\CreateChapterTestRequest;
use Edhub\CMS\Containers\Chapter\UI\Requests\UpdateChapterTestRequest;
use Edhub\CMS\Containers\Chapter\UI\Transformers\ChapterTestTransformer;
use Edhub\Shared\UI\BaseApiController;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface as Logger;

class ChapterTestController extends BaseApiController
{
    public function show(int $course, int $chapter, int $test, GetChapterTestAction $action)
    {
        $chapterTest = $action->run(new GetChapterTestTransporter(['id' => $test, 'course'=>$course, 'chapter'=>$chapter]));
        $transformedChapterTest = $this->transform($chapterTest, new ChapterTestTransformer(), ['answers']);
        $transformedChapterTest['answers'] = $transformedChapterTest['answers']['data'] ?? [];

        return $this->success([
            'test' => $transformedChapterTest
        ]);
    }

    public function store(int $course, int $chapter, CreateChapterTestRequest $request, CreateChapterTestAction $action, Logger $logger)
    {
        $request->merge(compact('chapter','course'));
        try {
            /** @var CreateChapterTestTransporter $transporter */
            $transporter = $request->toTransporter();

            DB::beginTransaction();
            $chapterTest = $action->run($transporter);
            DB::commit();

            $logger->info(
                sprintf('Test %d is created for paragraph %d', $chapterTest->id()->value(), $chapter),
                ['type' => 'courses.chapters.tests.create.success']
            );

            return $this->success([
                'test' => $this->transformChapterTest($chapterTest)
            ]);
        } catch (\Throwable $exception) {
            DB::rollBack();
            $logger->error(
                sprintf('Test was not created for paragraph %d. Reason: %s', $chapter, $exception->getMessage()),
                ['type' => 'courses.chapters.tests.create.failed']
            );
            throw $exception;
        }
    }

    public function update(int $course, int $chapter, int $testId, UpdateChapterTestRequest $request, UpdateChapterTestAction $action, Logger $logger)
    {
        $request->merge(['id' => $testId]);
        try {
            /** @var UpdateChapterTestTransporter $transporter */
            $transporter = $request->toTransporter();

            DB::beginTransaction();
            $chapterTest = $action->run($transporter);
            DB::commit();

            $logger->info(
                sprintf('Test %d is updated for paragraph %d', $testId, $chapter),
                ['type' => 'courses.chapters.tests.update.success']
            );

            return $this->success([
                'test' => $this->transformChapterTest($chapterTest)
            ]);

        } catch (\Throwable $exception) {
            DB::rollBack();
            $logger->error(
                sprintf('Test %d was not updated for paragraph %d. Reason: %s', $testId, $chapter, $exception->getMessage()),
                ['type' => 'courses.chapters.tests.update.failed']
            );
            throw $exception;
        }
    }

    public function delete(int $course, int $chapterId, int $testId, DeleteChapterTestAction $deleteChapterTest, Logger $logger)
    {
        try {
            /** @var DeleteChapterTestTransporter $transporter */
            DB::beginTransaction();
            $deleteChapterTest->
            run(new DeleteChapterTestTransporter(['id' => $testId]));
            DB::commit();

            $logger->info(
                sprintf('Test %d is deleted.', $testId),
                ['type' => 'courses.chapters.tests.delete.success']
            );

            return $this->success();

        } catch (\Throwable $exception) {
            DB::rollBack();
            $logger->error(
                sprintf('Test %d was not deleted. Reason: %s', $testId, $exception->getMessage()),
                ['type' => 'courses.chapters.tests.delete.failed']
            );
            throw $exception;
        }
    }

    /**
     * @param $chapterTest
     * @return array
     */
    private function transformChapterTest($chapterTest): array
    {
        return $this->transform($chapterTest, ChapterTestTransformer::class, ['tests']);
    }
}