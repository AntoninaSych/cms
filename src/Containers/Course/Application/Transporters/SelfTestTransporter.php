<?php

namespace Edhub\CMS\Containers\Course\Application\Transporters;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read int $type
 * @property-read string $correctAnswer
 * @property-read string $incorrectAnswer
 */
class SelfTestTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],
            'title' => ['type' => 'string'],
            'type' => ['type' => 'integer'],
            'correctAnswer' => ['type' => 'string'],
            'incorrectAnswer' => ['type' => 'string'],
            'answers' => [
                'type' => 'array',
                'items' => [
                    '$ref' => SelfTestAnswerTransporter::class
                ]
            ]
        ]
    ];
}