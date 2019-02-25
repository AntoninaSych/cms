<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\GetChapter;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $id
 */
class GetChapterTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer']
        ]
    ];
}