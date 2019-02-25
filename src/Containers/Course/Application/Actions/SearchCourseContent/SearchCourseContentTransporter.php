<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\SearchCourseContent;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $course
 * @property-read string $query
 */
class SearchCourseContentTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'course' => ['type' => 'integer'],
            'query' => ['type' => 'string'],
        ],
    ];
}