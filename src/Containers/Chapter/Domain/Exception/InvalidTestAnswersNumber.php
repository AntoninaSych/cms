<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Exception;


use Edhub\Shared\Exceptions\BaseException;
use Edhub\Shared\Exceptions\ErrorKeysDictionary;

class InvalidTestAnswersNumber extends BaseException
{
    protected $message = 'You need specify 1 right answer.';

    protected $code = 422;

    protected $messageId = ErrorKeysDictionary::SPECIFY_RIGHT_ANSWER;
}