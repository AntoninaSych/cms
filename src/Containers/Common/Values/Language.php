<?php

namespace Edhub\CMS\Containers\Common\Values;

use Edhub\Shared\Exceptions\InvalidArgumentException;

final class Language
{
    /**
     * @var string
     */
    private $language;

    private function __construct(string $language)
    {
        if (!in_array($language, ['en', 'nl'])) {
            throw new InvalidArgumentException(sprintf('Language %s is invalid.', $language));
        }
        $this->language = $language;
    }

    public static function new(string $language)
    {
        return new self($language);
    }

    public function value(): string
    {
        return $this->language;
    }

    public function __toString()
    {
        return $this->value();
    }
}
