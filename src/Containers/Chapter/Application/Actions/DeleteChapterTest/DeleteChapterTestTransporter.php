<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\DeleteChapterTest;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $id
 */
class DeleteChapterTestTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],
        ]
    ];
}