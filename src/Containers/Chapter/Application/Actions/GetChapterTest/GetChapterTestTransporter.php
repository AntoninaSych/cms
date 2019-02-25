<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\GetChapterTest;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $id
 */
class GetChapterTestTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer']
        ]
    ];
}