<?php

namespace spec\Edhub\CMS\Containers\Courses\Domain\Values;

use Edhub\CMS\Containers\Courses\Domain\Values\Language;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LanguageSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Language::class);
    }

    public function it_should_keep_english_language()
    {
        $this->beConstructedThrough('new', ['en']);

        $this->value()->shouldBe('en');
    }

    public function it_should_keep_dutch_language()
    {
        $this->beConstructedThrough('new', ['nl']);

        $this->value()->shouldBe('nl');
    }

    public function it_should_become_a_string()
    {
        $this->beConstructedThrough('new', ['en']);

        $this->__toString()->shouldBe('en');
    }
}
