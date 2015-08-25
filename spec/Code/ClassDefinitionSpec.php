<?php

namespace spec\Eventity\Code;

use PhpSpec\ObjectBehavior;

final class ClassDefinitionSpec extends ObjectBehavior
{
    public function it_returns_just_the_class_name_as_the_fqcn()
    {

        $this->beConstructedWith('TestClass', 'TestNamespace', [], '', [], []);

        $this->getFQCN()->shouldReturn('TestNamespace\TestClass');
    }

    public function it_returns_just_the_class_name_as_the_fqcn_if_namespace_is_empty()
    {

        $this->beConstructedWith('TestClass', '', [], '', [], []);

        $this->getFQCN()->shouldReturn('TestClass');
    }
}
