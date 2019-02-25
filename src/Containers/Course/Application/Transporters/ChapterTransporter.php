<?php

namespace Edhub\CMS\Containers\Course\Application\Transporters;

use Edhub\Shared\Dto\BaseDto;
/**
 * @property-read int $id
 * @property-read string $title
 * @property-read int $type
 * @property-read ChapterTransporter[] $children
 */
class ChapterTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],
            'title' => ['type' => 'string'],
            'type' => ['type' => 'integer'],
            'content' => ['type' => 'array'],
            'tests' => [
                'type' => 'array',
                'items' => [
                    '$ref' => SelfTestTransporter::class
                ]
            ],
            'children' => [
                'type' => 'array',
                'items' => [
                    '$ref' => ChapterTransporter::class
                ]
            ]
        ]
    ];
}