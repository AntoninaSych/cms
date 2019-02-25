<?php

namespace Edhub\CMS\Containers\LearningPath\Application\Transporters;

use Edhub\Shared\Dto\BaseDto;
use Edhub\CMS\Containers\Course\Application\Transporters\CourseTransporter;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string $subtitle
 * @property-read array $description
 * @property-read int $status
 * @property-read string $language
 * @property-read int $points
 * @property-read string $code
 * @property-read array $image
 * @property-read array $courses
 * @property-read array $documents
 * @property-read array $meta
 */
class LearningPathTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],
            'title' => ['type' => 'string'],
            'subtitle' => ['type' => 'string'],
            'description' => ['type' => 'array'],
            'status' => ['type' => 'integer'],
            'language' => ['type' => 'integer'],
            'points' => ['type' => 'integer'],
            'code' => ['type' => 'string'],
            'meta' => ['type' => 'object'],
            'documents' => [
                'type' => 'array',
                'properties' => [
                    'title' => ['type' => 'string'],
                    'description' => ['type' => 'string'],
                    'link' => ['type' => 'string'],
                ]
            ],
            'image' => [
                'type' => 'object',
                'properties' => [
                    'preview' => ['type' => 'string'],
                    'featured' => ['type' => 'string']
                ]
            ],
            'courses' => [
                'type' => 'array',
                'items' => [
                    '$ref' => CourseTransporter::class
                ]
            ]
        ]
    ];
}