<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\CreateCourse;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read string $title
 * @property-read string $subtitle
 * @property-read array $description
 * @property-read string $language
 * @property-read int $status
 * @property-read string $image
 * @property-read array $companies
 * @property-read int[] $categories
 * @property-read array $chapters
 */
class CreateCourseTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
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
            'chapters' => ['type' => 'array',]
        ],
        'default' => [
            'title' => '',
            'subtitle' => '',
            'description' => '',
            'language' => '',
            'status' => 0,
            'categories' => [],
            'chapters' => [],
        ]
    ];
}
