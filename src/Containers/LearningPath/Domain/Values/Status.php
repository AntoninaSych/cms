<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Values;

use Edhub\Shared\Exceptions\InvalidArgumentException;

final class Status
{
    private $rawStatus;
    private CONST DRAFT = 1;
    private CONST PUBLISHED = 2;
    private CONST ARCHIVED = 3;

    private function __construct(int $rawStatus)
    {
        $this->changeStatus($rawStatus);
    }

    public static function new(int $rawStatus): Status
    {
        return new self($rawStatus);
    }

    private function changeStatus(int $rawStatus):void
    {
        $this->validateStatus($rawStatus);
        $this->rawStatus = $rawStatus;
    }

    public function value(): int
    {
        return $this->rawStatus;
    }

    public static function archived(): Status
    {
        return new self(self::ARCHIVED);
    }

    public static function published(): Status
    {

        return new self(self::PUBLISHED);
    }

    public static function draft(): Status
    {
        return new self(self::DRAFT);
    }

    private static function all(): array
    {
        return [
            self::DRAFT,
            self::PUBLISHED,
            self::ARCHIVED
        ];
    }

    public function validateStatus(int $rawStatus)
    {
        if (empty(trim($rawStatus))) {
            throw new InvalidArgumentException('Provided status is empty.', 422);
        }

        if (!in_array($rawStatus, self::all())) {
            throw new InvalidArgumentException('Learning path status is invalid.', 422);
        }
    }
}