<?php

namespace Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $page
 * @property-read int $perPage
 * @property-read array $filters
 */
class GetLearningPathListTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'page' => ['type' => 'integer'],
            'perPage' => ['type' => 'integer'],
            'filters' => [
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'name' => ['type' => 'string'],
                        'value' => ['type' => 'array'],
                    ]
                ]
            ]
//            'sorting' => [
//                'type' => 'array',
//            ]
        ],
        'additionalProperties' => true
    ];
}
