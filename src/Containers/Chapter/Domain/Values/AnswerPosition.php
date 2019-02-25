<?php
namespace Edhub\CMS\Containers\Chapter\Domain\Values;

use Edhub\Shared\Exceptions\InvalidArgumentException;

class AnswerPosition
{
    /**
     * @var int
     */
    private $position;

    private function __construct(int $position)
    {
        if (empty(trim($position))) {
            throw new InvalidArgumentException('Answer position is invalid.', 422);
        }
        $this->position = $position;
    }

    public static function new(int $position): AnswerPosition
    {
        return new self($position);
    }

    public function value(): int
    {
        return $this->position;
    }
}