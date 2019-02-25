<?php


namespace Edhub\CMS\Containers\Course\Domain\Exceptions;

use Edhub\Shared\Exceptions\ErrorKeysDictionary;
use Edhub\Shared\Exceptions\NotFound;

class CourseNotFound extends NotFound
{
    protected $messageId = ErrorKeysDictionary::COURSE_NOT_FOUND;

    protected $message = 'Course not exists or not allowed for this user/company.';
}