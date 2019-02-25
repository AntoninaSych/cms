<?php


namespace Edhub\CMS\Containers\Course\Domain\Exceptions;


use Edhub\Shared\Exceptions\DomainException;
use Edhub\Shared\Exceptions\ErrorKeysDictionary;

class InvalidCourseCompany  extends DomainException
{
    protected $messageId = ErrorKeysDictionary::INVALID_COURSE_COMPANY;

    protected $message = "Course should belong to at least 1 company.";
}