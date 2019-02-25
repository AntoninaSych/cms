<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Exception;


use Edhub\Shared\Exceptions\BaseException;
use Edhub\Shared\Exceptions\ErrorKeysDictionary;

class FewAnswersForTest extends BaseException
{
    protected $message = 'The number of answers in the test must be greater than 1';

    protected $code = 422;

    protected $messageId = ErrorKeysDictionary::NUMBER_OF_ANSWERS;
}