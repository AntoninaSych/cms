<?php

namespace Edhub\CMS\Containers\CourseCategory\Application\Actions\GetCourseCategory;

use Edhub\Shared\Dto\BaseDto;

class GetCourseCategoryTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],
        ],
    ];
}