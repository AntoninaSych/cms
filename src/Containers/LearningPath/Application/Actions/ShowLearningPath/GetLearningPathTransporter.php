<?php
namespace Edhub\CMS\Containers\LearningPath\Application\Actions\ShowLearningPath;

use Edhub\Shared\Dto\BaseDto;

class GetLearningPathTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'number'],
            'filters' => [
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'name' => ['type' => 'string'],
                        'value' => ['type' => 'array'],
                    ]
                ]
            ]
        ],
        'required' => ['id']
    ];
}