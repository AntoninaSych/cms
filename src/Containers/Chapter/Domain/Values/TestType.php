<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Values;

use Edhub\Shared\Assertions\Assertion;

final class TestType
{
    private const SINGLE_ANSWER_CHOICE = 1;
    private const MULTIPLE_ANSWER_CHOICE = 2;

    /**
     * @var int
     */
    private $type;

    private function __construct(int $type)
    {
        Assertion::inArray($type, [self::SINGLE_ANSWER_CHOICE, self::MULTIPLE_ANSWER_CHOICE], 'Test has no type '.$type);
        $this->type = $type;
    }

    public static function new(int $type): TestType
    {
        return new self($type);
    }

    public static function singleAnswer(): TestType
    {
        return self::new(self::SINGLE_ANSWER_CHOICE);
    }

    public function value(): int
    {
        return $this->type;
    }

    public function isEqualTo(TestType $testType): bool
    {
        return $this->value() === $testType->value();
    }
}