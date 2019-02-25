<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\UpdateChapterTest;

use Edhub\Shared\Dto\BaseDto;

class UpdateChapterTestTransporter extends BaseDto
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