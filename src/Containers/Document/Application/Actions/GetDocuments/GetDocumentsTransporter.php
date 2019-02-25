<?php


namespace Edhub\CMS\Containers\Document\Application\Actions\GetDocuments;


use Edhub\Shared\Dto\BaseDto;

/**
 * @property-read int $id
 */
class GetDocumentsTransporter extends BaseDto
{

    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'integer']
        ]
    ];
}