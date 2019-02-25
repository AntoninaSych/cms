<?php
namespace Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList;

use Edhub\Shared\Dto\BaseDto;

/**
 * @TODO Add @property-read for all expected properties
 * @property-read string $image
 */
class UpdateLearningPathTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],
            'title' => ['type' => 'string'],
            'subtitle' => ['type' => 'string'],
            'description' => ['type' => 'array'],
            'status' => ['type' => 'integer'],
            'language' => ['type' => 'string'],
            'points' => ['type' => 'integer'],//max 30 value
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
            ]
        ],
        'required' => ['id','title', 'status'],
        'default' => [
            'subtitle' => '',
            'description' => '',
            'points' => 0,
            'code' => '',
            'language' => 'en'
        ]
    ];
}