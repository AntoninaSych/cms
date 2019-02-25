<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Values;

use Edhub\Shared\Assertions\Assertion;

final class ChapterType
{
    private const TEXT = 1;
    private const TEXT_ASSIGNMENT = 2;
    private const VIDEO = 3;
    private const BUKU = 4;
    private const SCRIMBA = 5;
    private const SANDBOX = 6;
    private const SELF_TEST = 7;
    /** @var int */
    private $type;

    private function __construct(int $type)
    {
        Assertion::inArray($type, self::allAsRaw(), "Chapter has no type {$type}");
        $this->type = $type;
    }

    /** @return ChapterType[] */
    public static function all(): iterable
    {
        return [
            self::text(),
            self::new(self::TEXT_ASSIGNMENT),
            self::new(self::VIDEO),
            self::new(self::BUKU),
            self::new(self::SCRIMBA),
            self::new(self::SANDBOX),
            self::selfTest(),
        ];
    }

    public static function new(int $type): ChapterType
    {
        return new self($type);
    }

    public static function text(): ChapterType
    {
        return self::new(self::TEXT);
    }

    public static function selfTest(): ChapterType
    {
        return self::new(self::SELF_TEST);
    }

    public function isEqualTo(ChapterType $chapterType): bool
    {
        return $this->value() === $chapterType->value();
    }

    public function value(): int
    {
        return $this->type;
    }

    /** @return int[] */
    private static function allAsRaw(): array
    {
        return [
            self::TEXT,
            self::TEXT_ASSIGNMENT,
            self::VIDEO,
            self::BUKU,
            self::SCRIMBA,
            self::SANDBOX,
            self::SELF_TEST,
        ];
    }
}