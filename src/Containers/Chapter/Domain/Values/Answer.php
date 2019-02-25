<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Values;

use Edhub\Shared\Exceptions\InvalidArgumentException;

final class Answer
{
    /**
     * @var string
     */
    private $answer;

    private function __construct(string $answer)
    {
        $this->validate($answer);
        $this->answer = $answer;
    }

    public static function new(string $answer): Answer
    {
        return new self($answer);
    }

    public function value(): string
    {
        return $this->answer;
    }

    public function validate(string $answer)
    {
        if (empty(trim($answer))) {
            throw new InvalidArgumentException('Provided answer is empty', 422);
        }
    }

    public function __toString()
    {
        return $this->value();
    }


}