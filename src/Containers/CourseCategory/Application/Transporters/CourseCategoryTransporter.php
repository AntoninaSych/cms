<?php

namespace Edhub\CMS\Containers\CourseCategory\Application\Transporters;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $id
 * @property-read array $title
 */
class CourseCategoryTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],
            'title' => ['type' => 'object']
        ]
    ];
}