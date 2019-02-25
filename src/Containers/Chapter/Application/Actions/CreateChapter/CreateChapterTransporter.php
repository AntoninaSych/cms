<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapter;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $course
 * @property-read string $title
 * @property-read string $subtitle
 * @property-read array $content
 * @property-read integer $type
 * @property-read string $extraLink
 */
class CreateChapterTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'course' => ['type' => 'integer'],
            'title' => ['type' => 'string'],
            'subtitle' => ['type' => 'string'],
            'content' => ['type' => 'array'],
            'type' => ['type' => 'integer'],
            'extraLink' => ['type' => 'string'],
        ]
    ];
}