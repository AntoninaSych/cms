<?php


namespace Edhub\CMS\Containers\Document\Domain\Values;


use Edhub\Shared\Exceptions\InvalidArgumentException;

final class DocumentType
{
    private $rawType;
    private CONST LEARNING_PATH = 1;


    private function __construct(int $rawType)
    {
        $this->validateType($rawType);
        $this->rawType = $rawType;

    }

    public static function new(int $rawType): DocumentType
    {
        return new self($rawType);
    }

    public function value(): int
    {
        return $this->rawType;
    }

    public function isEqual(DocumentType $type)
    {
        return $this->value() === $type->value();
    }

    public static function learningPath(): DocumentType
    {
        return new self(self::LEARNING_PATH);
    }

    private static function all(): array
    {
        return [
            self::LEARNING_PATH
        ];
    }

    private function validateType(int $rawType)
    {
        if (empty($rawType)) {
            throw new InvalidArgumentException('Provided document type is empty.', 422);
        }

        if (!in_array($rawType, self::all())) {
            throw new InvalidArgumentException('Document type is invalid.', 422);
        }
    }
}