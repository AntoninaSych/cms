<?php

namespace Edhub\CMS\Containers\Course\Domain\Exceptions;

use Edhub\Shared\Exceptions\BaseException;
use Edhub\Shared\Exceptions\ErrorKeysDictionary;

class CourseNotSaved  extends BaseException
{
    protected $message = 'Course not saved';

    protected $messageId = ErrorKeysDictionary::COURSE_NOT_SAVED;
}