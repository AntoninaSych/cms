<?php

namespace Edhub\CMS\Containers\Course\Application\Transporters;

use Edhub\Shared\Dto\BaseDto;
/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string $subtitle
 * @property-read array $description
 * @property-read int $status
 * @property-read int[] $categories
 * @property-read ChapterTransporter[] $chapters
 * @property-read object $image
 */
class CourseTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],
            'title' => ['type' => 'string'],
            'subtitle' => ['type' => 'string'],
            'description' => ['type' => 'array'],
            'status' => ['type' => 'integer'],
            'image' => [
                'type' => 'object',
                'properties' => [
                    'preview' => ['type' => 'string'],
                    'featured' => ['type' => 'string'],
                ]
            ],
            'categories' => [
                'type' => 'array',
                'items' => ['type' => 'integer']
            ],
            'chapters' => [
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                    '$ref' => ChapterTransporter::class
                ]
            ],
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
        ]
    ];
}