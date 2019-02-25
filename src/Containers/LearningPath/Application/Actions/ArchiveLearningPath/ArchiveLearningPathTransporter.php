<?php

namespace Edhub\CMS\Containers\LearningPath\Application\Actions\ArchiveLearningPath;

use Edhub\Shared\Dto\BaseDto;

class ArchiveLearningPathTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'number']
        ],
        'required' => ['id']
    ];
}