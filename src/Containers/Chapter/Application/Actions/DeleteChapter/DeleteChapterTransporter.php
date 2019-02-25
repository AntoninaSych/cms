<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\DeleteChapter;

use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $id
 */
class DeleteChapterTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer']
        ]
    ];
}