<?php


namespace Edhub\CMS\Containers\Common\Exceptions;


class InvalidCourseStatus extends DomainException
{
    protected $messageId = ErrorKeysDictionary::INVALID_COURSE_STATUS;

    protected $message = "Course status is not implemented.";
}