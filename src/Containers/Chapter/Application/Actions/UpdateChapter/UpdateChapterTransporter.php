<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\UpdateChapter;

use Edhub\Shared\Dto\BaseDto;
/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string $subtitle
 * @property-read array $content
 * @property-read integer $type
 * @property-read string $extraLink
 * @property-read int[] $tests
 */
class UpdateChapterTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],
            'title' => ['type' => 'string'],
            'subtitle' => ['type' => 'string'],
            'content' => ['type' => 'array'],
            'type' => ['type' => 'integer'],
            'extraLink' => ['type' => 'string'],
            'tests' => [
                'type' => 'array',
                'items' => ['type' => 'integer']
            ]
        ]
    ];
}