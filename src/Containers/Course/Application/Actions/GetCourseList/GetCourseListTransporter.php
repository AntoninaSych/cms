<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\GetCourseList;

use Edhub\Shared\Dto\BaseDto;

class GetCourseListTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'filters' => [
            'type' => 'array',
            'items' => [
                'type' => 'object',
                'properties' => [
                    'name' => ['type' => 'string'],
                    'value' => ['type' => 'array'],
                ],
            ]
        ],
        'sorting' => ['type' => 'array'],
    ];
}
