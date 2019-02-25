<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\GetCourseStatusList;

use Edhub\CMS\Containers\Course\Domain\Values\CourseStatus;

class GetCourseStatusListAction
{
    /**
     * @return CourseStatus[]
     */
    public function run(): array
    {
        return CourseStatus::all();
    }
}