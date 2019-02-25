<?php

namespace Edhub\CMS\Containers\CourseCategory\Application\Actions\GetCourseCategoryList;

use Edhub\Shared\Collections\PaginatedCollection;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategoryRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class GetCourseCategoryListAction
{
    /**
     * @var CourseCategoryRepository
     */
    private $categories;

    public function __construct(CourseCategoryRepository $categories)
    {
        $this->categories = $categories;
    }

    public function run(GetCourseCategoryListTransporter $transporter): PaginatedCollection
    {
        $perPage = $transporter->perPage ?? CourseCategoryRepository::DEFAULT_LIMIT;
        /** @var LengthAwarePaginator $categories */
        $categories = $this->categories->paginate($perPage);

        return new PaginatedCollection(
            iterator_to_array($categories),
            $categories->total(),
            $perPage
        );
    }

}