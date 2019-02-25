<?php

namespace Edhub\CMS\Containers\Course\Application\Transporters;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $id
 * @property-read bool $isCorrect
 * @property-read string $answer
 * @property-read int $position
 */
class SelfTestAnswerTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],
            'isCorrect' => ['type' => 'boolean'],
            'answer' => ['type' => 'string'],
            'position' => ['type' => 'integer'],
        ]
    ];
}