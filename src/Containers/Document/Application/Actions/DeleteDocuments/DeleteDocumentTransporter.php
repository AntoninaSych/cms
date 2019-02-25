<?php


namespace Edhub\CMS\Containers\Document\Application\Actions\DeleteDocuments;


use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $id
 */
class DeleteDocumentTransporter extends BaseDto
{
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer'],

        ]
    ];
}