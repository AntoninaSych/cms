<?php

namespace Edhub\CMS\Containers\Course\UI\Controllers;

use Edhub\Shared\UI\BaseApiController;
use Edhub\CMS\Containers\Course\Application\Actions\CreateCourse\CreateCourseAction;
use Edhub\Shared\UI\Requests\FilterableRequest;
use Edhub\CMS\Containers\Course\Application\Actions\CreateCourse\CreateCourseTransporter;
use Edhub\CMS\Containers\Course\Application\Actions\DeleteCourse\DeleteCourseAction;
use Edhub\CMS\Containers\Course\Application\Actions\DeleteCourse\DeleteCourseTransporter;
use Edhub\CMS\Containers\Course\Application\Actions\GetCourse\GetCourseAction;
use Edhub\CMS\Containers\Course\Application\Actions\GetCourse\GetCourseTransporter;
use Edhub\CMS\Containers\Course\Application\Actions\GetCourseList\GetCourseListAction;
use Edhub\CMS\Containers\Course\Application\Actions\GetCourseList\GetCourseListTransporter;
use Edhub\CMS\Containers\Course\Application\Actions\GetCourseStatusList\GetCourseStatusListAction;
use Edhub\CMS\Containers\Course\Application\Actions\UpdateCourse\UpdateCourseAction;
use Edhub\CMS\Containers\Course\Application\Actions\UpdateCourse\UpdateCourseTransporter;
use Edhub\Shared\Values\PaginationPresenter;
use Edhub\CMS\Containers\Course\UI\Requests\CreateCourseRequest;
use Edhub\CMS\Containers\Course\UI\Requests\UpdateCourseRequest;
use Edhub\CMS\Containers\Course\UI\Transformers\CourseStatusTransformer;
use Edhub\CMS\Containers\Course\UI\Transformers\CourseTransformer;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Support\Facades\DB;


class CourseController extends BaseApiController
{
    public function statuses(GetCourseStatusListAction $courseStatusListAction)
    {
        $courseStatuses = $courseStatusListAction->run();

        return $this->success([
            'statuses' => $this->transform($courseStatuses, new CourseStatusTransformer()),
        ]);
    }

    public function list(FilterableRequest $request, GetCourseListAction $action)
    {
        $page = $request->get('page', 1);
        $courses = $action->run(new GetCourseListTransporter([
            'filters' => $request->filters(),
            'sorting' => $request->sorting(),
            'perPage' => $request->get('perPage')
        ]));

        $paginationOption = new PaginationPresenter($courses->perPage(), $page, $courses->total());
        $meta =   $paginationOption->display() ;
        return $this->success([
            'courses' => $this->transform($courses, CourseTransformer::class, [], $meta),
        ]);
    }

    public function show(int $id, GetCourseAction $action)
    {
        $course = $action->run(new GetCourseTransporter(compact('id')));

        return $this->success([
            'course' => $this->transformCourse($course)
        ]);
    }

    public function update(int $id, UpdateCourseRequest $request, UpdateCourseAction $action, Logger $logger)
    {
        $request->merge(compact('id'));

        try {
            /** @var UpdateCourseTransporter $transporter */
            $transporter = $request->toTransporter();

            DB::beginTransaction();
            $course = $action->run($transporter);
            DB::commit();

            $logger->info('Course is created.', ['type' => 'courses.create.success']);
            $transformedCourse = $this->transformCourse($course);

            return $this->success([
                'course' => $transformedCourse,
            ]);

        } catch (\Throwable $exception) {
            DB::rollBack();
            $logger->error($exception->getMessage(), ['type' => 'courses.create.failed']);
            throw $exception;
        }
    }

    public function store(CreateCourseRequest $request, CreateCourseAction $action, Logger $logger)
    {
        try {
            /** @var CreateCourseTransporter $transporter */
            $transporter = $request->toTransporter();
            DB::beginTransaction();
            $course = $action->run($transporter);
            DB::commit();

            $logger->info('Course is created.', ['type' => 'courses.create.success']);


            return $this->success([
                'course' => $this->transformCourse($course)
            ]);

        } catch (\Throwable $exception) {
            DB::rollBack();
            $logger->error($exception->getMessage(), ['type' => 'courses.create.failed']);
            throw $exception;
        }
    }

    public function delete(int $id, DeleteCourseAction $action, Logger $logger)
    {
        try {
            DB::beginTransaction();
            $action->run(new DeleteCourseTransporter(compact('id')));
            DB::commit();

            $logger->info("Course {$id} is deleted.", ['type' => 'courses.delete.success']);

            return $this->success();
        } catch (\Throwable $exception) {
            DB::commit();
            $logger->info("Course {$id} is not deleted.", ['type' => 'courses.delete.failed', 'reason' => $exception->getMessage()]);
            throw $exception;
        }
    }


    /**
     * @param $course
     * @return array
     */
    private function transformCourse($course): array
    {
        $transformedCourse = $this->transform($course, CourseTransformer::class, ['chapters.children']);
        // Remove 'data' key from chapters due to front-end library
        $transformedCourse['chapters'] = $this->removeCourseChaptersDataKey($transformedCourse['chapters']['data']);
        // Sort chapters by position
        usort($transformedCourse['chapters'], function (array $chapter1, array $chapter2) {
            return $chapter1['position'] <=> $chapter2['position'];
        });

        return $transformedCourse;
    }

    private function removeCourseChaptersDataKey(array $chapters): array
    {
        foreach ($chapters as $key => $chapter) {
            $chapter['children'] = $this->removeCourseChaptersDataKey($chapter['children']['data'] ?? []);
            $chapters[$key] = $chapter;
        }

        return $chapters;
    }
}