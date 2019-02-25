<?php

namespace Edhub\CMS\Containers\CourseCategory\Domain;

use Edhub\Shared\Collections\PaginatedCollection;
use Edhub\CMS\Containers\Course\Domain\Collections\Categories;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategory;
use Prettus\Repository\Eloquent\BaseRepository;

class CourseCategoryRepository extends BaseRepository
{
    const DEFAULT_LIMIT = 20;

    public function model()
    {
        return CourseCategory::class;
    }

    public function getOne(int $id): CourseCategory
    {
        $category = CourseCategory::query()->findOrFail($id);

        return $category;
    }

    public function findByIds(int ...$ids): Categories
    {
        return new Categories(
            CourseCategory::query()->findMany($ids)->all()
        );
    }

    public function save(CourseCategory $category): void
    {
        $category->saveOrFail();
    }

    public function all($columns = ['*'])
    {
        return parent::all($columns)->all();
    }

}