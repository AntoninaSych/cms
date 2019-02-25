<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\GetCourse;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $id
 */
class GetCourseTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],
        ],
    ];
}