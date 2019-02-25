<?php


namespace Edhub\CMS\Containers\Chapter\Domain\Exception;

use Edhub\Shared\Exceptions\ErrorKeysDictionary;
use Edhub\Shared\Exceptions\NotFound;

class ChapterTestNotFound extends NotFound
{
    protected $messageId = ErrorKeysDictionary::CHAPTER_TEST_NOT_FOUND;

    protected $message = 'Chapter test is not found.';

}
