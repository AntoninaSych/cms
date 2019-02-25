<?php

namespace Edhub\CMS\Containers\LearningPath\Application\Actions\CreateLearningPath;

use Edhub\Shared\Dto\BaseDto;

/**
 * @TODO Add @property-read for all expected properties
 * @property-read string $image
 */
class CreateLearningPathTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'title' => ['type' => 'string'],
            'subtitle' => ['type' => 'string'],

            'description' => ['type' => 'array'],
            'status' => ['type' => 'integer'],
            'points' => ['type' => 'integer'],
            'code' => ['type' => 'string'],
            'meta' => ['type' => 'object'],
            'image' => ['type' => 'string'],
            'courses' => [
                'type' => 'array',
                'items' => ['type' => 'integer']
            ],
            'companies' => [
                'type' => 'array',
                'items' => ['type' => 'integer']
            ],
            'language' => ['type' => 'string'],
        ],
        'required' => ['title', 'status'],
        'default' => [
            'subtitle' => '',
            'description' => '',
            'points' => 0,
            'code' => '',
            'courses' => '',
            'companies' => [],
            'language' => 'en'
        ]
    ];
}
