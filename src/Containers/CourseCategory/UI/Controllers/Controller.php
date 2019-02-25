<?php

namespace Edhub\CMS\Containers\CourseCategory\UI\Controllers;

use Edhub\Shared\UI\BaseApiController;
use Edhub\Shared\UI\Requests\FilterableRequest;
use Edhub\Shared\Values\PaginationPresenter;
use Edhub\CMS\Containers\CourseCategory\Application\Actions\CreateCourseCategory\CreateCourseCategoryAction;
use Edhub\CMS\Containers\CourseCategory\Application\Actions\GetCourseCategory\GetCourseCategoryAction;
use Edhub\CMS\Containers\CourseCategory\Application\Actions\GetCourseCategory\GetCourseCategoryTransporter;
use Edhub\CMS\Containers\CourseCategory\Application\Actions\GetCourseCategoryList\GetCourseCategoryListAction;
use Edhub\CMS\Containers\CourseCategory\Application\Actions\GetCourseCategoryList\GetCourseCategoryListTransporter;
use Edhub\CMS\Containers\CourseCategory\Application\Actions\UpdateCourseCategory\UpdateCourseCategoryAction;
use Edhub\CMS\Containers\CourseCategory\UI\Request\CreateCourseCategoryRequest;
use Edhub\CMS\Containers\CourseCategory\UI\Request\UpdateCourseCategoryRequest;
use Edhub\CMS\Containers\CourseCategory\UI\Transformers\CourseCategoryTransformer;
use Psr\Log\LoggerInterface as Logger;

class Controller extends BaseApiController
{
    public function list(FilterableRequest $request, GetCourseCategoryListAction $action)
    {
        $page = $request->get('page', 1);
        $courseCategories = $action->run(new GetCourseCategoryListTransporter([
            'page' => $page,
            'perPage' => $request->get('perPage')
        ]));
        $paginationPresenter = new PaginationPresenter($courseCategories->perPage(), $page, $courseCategories->total());
        $paginationOption = new PaginationPresenter($courseCategories->perPage(), $paginationPresenter->page(), $courseCategories->total());
        $meta =  $paginationOption->display();

        return $this->success([
            'categories' => $this->transform($courseCategories, CourseCategoryTransformer::class, [], $meta),
        ]);
    }

    public function show(int $id, GetCourseCategoryAction $action)
    {
        $courseCategory = $action->run(new GetCourseCategoryTransporter(compact('id')));

        return $this->success([
            'category' => $this->transform($courseCategory, CourseCategoryTransformer::class),
        ]);
    }

    public function store(CreateCourseCategoryRequest $request, CreateCourseCategoryAction $action, Logger $logger)
    {
        $transporter = $request->toTransporter();

        try {
            $courseCategory = $action->run($transporter);

            $logger->info('Course category is created.', ['course_category_id' => $courseCategory->id(), 'type' => 'courses.categories.create.success']);

            return $this->success([
                'category' => $this->transform($courseCategory, CourseCategoryTransformer::class),
            ]);
        } catch (\Throwable $exception) {
            $logger->error($exception->getMessage(), ['type' => 'courses.categories.create.failed']);
            throw $exception;
        }
    }

    public function update(int $id, UpdateCourseCategoryRequest $request, UpdateCourseCategoryAction $action, Logger $logger)
    {
        $transporter = $request->toTransporter();

        try {
            $courseCategory = $action->run($transporter);

            $logger->info('Course category is updated.', ['course_category_id' => $id, 'type' => 'courses.categories.update.success']);

            return $this->success([
                'category' => $this->transform($courseCategory, CourseCategoryTransformer::class),
            ]);
        } catch (\Throwable $exception) {
            $logger->error($exception->getMessage(), ['type' => 'courses.categories.update.failed']);
            throw $exception;
        }
    }
}