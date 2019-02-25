<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapterTest;

use Edhub\Shared\Dto\BaseDto;

class CreateChapterTestTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'chapter' => ['type' => 'integer'],
            'title' => ['type' => 'string'],
            'type' => ['type' => 'integer'],
            'correctAnswer' => ['type' => 'string'],
            'incorrectAnswer' => ['type' => 'string'],
            'answers' => [
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'title' => ['type' => 'string'],
                        'isCorrect' => ['type' => 'boolean']
                    ]
                ]
            ]
        ]
    ];
}