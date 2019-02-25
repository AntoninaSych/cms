<?php


namespace Edhub\CMS\Containers\Document\Domain\Exceptions;

use Edhub\Shared\Exceptions\ErrorKeysDictionary;
use Edhub\Shared\Exceptions\NotFound;

class DocumentNotFound extends NotFound
{
    protected $message = 'Document not found.';

    protected $messageId = ErrorKeysDictionary::DOCUMENT_NOT_FOUND;
}

