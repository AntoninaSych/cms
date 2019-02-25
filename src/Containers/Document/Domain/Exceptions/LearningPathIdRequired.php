<?php


namespace Edhub\CMS\Containers\Document\Domain\Exceptions;


use Edhub\Shared\Exceptions\DomainException;
use Edhub\Shared\Exceptions\ErrorKeysDictionary;

class LearningPathIdRequired extends DomainException
{
    protected $message = 'Learning path ID is required due to document type';

    protected $messageId = ErrorKeysDictionary::REQUIRED_LEARNING_PATH_ID;
}