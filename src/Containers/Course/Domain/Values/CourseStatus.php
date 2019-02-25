<?php

namespace Edhub\CMS\Containers\Course\Domain\Values;

use Edhub\CMS\Containers\Common\Exceptions\InvalidCourseStatus;

final class CourseStatus
{
    private CONST DRAFT = 1;
    private CONST PUBLISHED = 2;
    private CONST ARCHIVED = 3;

    private $status;

    private function __construct(int $status)
    {
        $this->status = $status;
    }

    public static function new(int $status): CourseStatus
    {
        switch ($status) {
            case self::DRAFT:
                return self::draft();
            case self::PUBLISHED:
                return self::published();
            case self::ARCHIVED:
                return self::archived();
        }

        throw new InvalidCourseStatus(sprintf('Course status with value %d is not implemented.', $status));
    }

    public static function archived(): CourseStatus
    {
        return new self(self::ARCHIVED);
    }

    public static function published(): CourseStatus
    {
        return new self(self::PUBLISHED);
    }

    public static function draft(): CourseStatus
    {
        return new self(self::DRAFT);
    }

    public static function all(): array
    {
        return [
            self::draft(),
            self::published(),
            self::archived(),
        ];
    }

    public function value(): int
    {
        return $this->status;
    }
}
