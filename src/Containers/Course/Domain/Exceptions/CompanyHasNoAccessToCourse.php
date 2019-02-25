<?php

namespace Edhub\CMS\Containers\Course\Domain\Exceptions;


use Edhub\Shared\Exceptions\BaseException;
use Edhub\Shared\Exceptions\ErrorKeysDictionary;

class CompanyHasNoAccessToCourse extends BaseException
{
    protected $message = 'Company has no access to course';

    private $info = [];

    protected $messageId = ErrorKeysDictionary::COMPANY_HAS_NO_ACCESS_TO_COURSE;

    public function __construct(int $company, int $course)
    {
        $this->info = compact('company', 'course');
        parent::__construct();
    }
}
