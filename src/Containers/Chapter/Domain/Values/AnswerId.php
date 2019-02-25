<?php
namespace Edhub\CMS\Containers\Chapter\Domain\Values;

use Edhub\Shared\Exceptions\InvalidArgumentException;

final class AnswerId
{
    /**
     * @var int
     */
    private $answerId;

    private function __construct(int $answerId)
    {
        if (empty($answerId)) {
            throw new InvalidArgumentException('Provided answer ID is empty.', 422);
        }
        $this->answerId = $answerId;
    }

    public static function new(int $answerId): AnswerId
    {
        return new self($answerId);
    }

    public function value(): int
    {
        return $this->answerId;
    }
    public function isEqualTo(AnswerId $answerId): bool
    {
        return $this->value() === $answerId->value();
    }
}