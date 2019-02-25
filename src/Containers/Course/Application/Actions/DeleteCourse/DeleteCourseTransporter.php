<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\DeleteCourse;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $id
 */
class DeleteCourseTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer']
        ]
    ];
}