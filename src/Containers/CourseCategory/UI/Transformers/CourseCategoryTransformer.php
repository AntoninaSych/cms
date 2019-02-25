<?php

namespace Edhub\CMS\Containers\CourseCategory\UI\Transformers;

use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategory;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategoryTitle;
use Edhub\Shared\UI\Transformers\BaseTransformer;

class CourseCategoryTransformer extends BaseTransformer
{
    public function transform(CourseCategory $category): array
    {
        return [
            'id' => $category->id(),
            'title' => $category->title(),
        ];
    }
}