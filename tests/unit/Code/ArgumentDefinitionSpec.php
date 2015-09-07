<?php

namespace unit\Eventity\Code;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class ArgumentDefinitionSpec extends ObjectBehavior
{
    public function it_is_not_typed_if_no_type_is_specfied()
    {
        $this->beConstructedCreate('name');

        $this->shouldNotBeTyped();
    }

    public function it_is_typed_if_a_type_is_specfied()
    {
        $this->beConstructedCreateWithType('array', 'name');

        $this->shouldBeTyped();
    }
}
