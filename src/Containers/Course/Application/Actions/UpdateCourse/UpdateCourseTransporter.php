<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\UpdateCourse;

use Edhub\Shared\Dto\BaseDto;
use Edhub\CMS\Containers\Course\Application\Transporters\ChapterTransporter;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string $subtitle
 * @property-read array $description
 * @property-read string $language
 * @property-read int $status
 * @property-read string $image
 * @property-read array $companies
 * @property-read int[] $categories
 * @property-read ChapterTransporter[] $chapters
 */
class UpdateCourseTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],
            'title' => ['type' => 'string'],
            'subtitle' => ['type' => 'string'],
            'description' => ['type' => 'array'],
            'language' => ['type' => 'string'],
            'status' => ['type' => 'integer'],
            'image' => ['type' => 'string'],
            'companies' => [
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'company' => ['type' => 'integer'],
                        'isPublic' => ['type' => 'boolean']
                    ]
                ]
            ],
            'categories' => [
                'type' => 'array',
                'items' => ['type' => 'integer']
            ],
            'chapters' => [
                'type' => 'array',
                'items' => [
                    '$ref' => ChapterTransporter::class,
                ]
            ]
        ],
        'default' => [
            'categories' => [],
            'chapters' => [],
        ]
    ];
}