<?php

namespace Edhub\CMS\Containers\CourseCategory\Application\Actions\GetCourseCategoryList;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $page
 * @property-read int $perPage
 */
class GetCourseCategoryListTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'page' => ['type' => 'integer'],
            'perPage' => ['type' => 'integer'],
        ],
        'additionalProperties' => true
    ];
}