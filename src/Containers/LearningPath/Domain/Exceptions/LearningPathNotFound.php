<?php


namespace Edhub\CMS\Containers\LearningPath\Domain\Exceptions;


use Edhub\Shared\Exceptions\ErrorKeysDictionary;
use Edhub\Shared\Exceptions\NotFound;

class LearningPathNotFound extends NotFound
{
    protected $message = 'Learning path not found.';

    protected $messageId = ErrorKeysDictionary::LEARNING_PATH_NOT_FOUND;
}