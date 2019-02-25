<?php

namespace Edhub\CMS\Containers\ContentMedia\Domain\Values;

class ContentMediaType
{
    private const IMAGE = 1;
    private const FILE = 2;
    private $rawStatus;

    private function __construct(int $rawStatus)
    {
        $this->rawStatus = $rawStatus;
    }

    public function value(): int
    {
        return $this->rawStatus;
    }

    public function isEqual(ContentMediaType $typeFile)
    {
        return $this->value() === $typeFile->value();
    }

    public static function image(): ContentMediaType
    {
        return new self(self::IMAGE);
    }

    public static function file(): ContentMediaType
    {
        return new self(self::FILE);
    }
}