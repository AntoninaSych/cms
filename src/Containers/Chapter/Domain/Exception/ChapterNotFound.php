<?php


namespace Edhub\CMS\Containers\Chapter\Domain\Exception;

use Edhub\Shared\Exceptions\ErrorKeysDictionary;
use Edhub\Shared\Exceptions\NotFound;

class ChapterNotFound extends NotFound
{
    protected $messageId = ErrorKeysDictionary::CHAPTER_NOT_FOUND;

    protected $message = 'Chapter is not found.';

}