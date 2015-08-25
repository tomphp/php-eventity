<?php

namespace spec\Eventity\Code;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\Code\ClassDefinition;
use Eventity\Code\MethodDefinition;

final class DefaultClassCodeRendererSpec extends ObjectBehavior
{
    function it_is_creates_an_empty_class()
    {
        $builder = ClassDefinition::builder('TestClass');

        $this->render($builder->build())->shouldReturn(<<<EOF
class TestClass
{
}
EOF
        );
    }

    public function it_creates_class_with_a_namespace()
    {
        $builder = ClassDefinition::builder('Test\Namespace\NSClass');

        $this->render($builder->build())->shouldReturn(<<<EOF
namespace Test\Namespace;

class NSClass
{
}
EOF
        );
    }

    function it_creates_a_class_which_extends_a_namespaced_parent()
    {
        $builder = ClassDefinition::builder('TestClass');
        $builder->setParent('Test\Namespace\Parent');

        $this->render($builder->build())->shouldReturn(<<<EOF
use Test\Namespace\Parent;

class TestClass extends Parent
{
}
EOF
        );
    }

    function it_creates_a_class_which_implements_interfaces()
    {
        $builder = ClassDefinition::builder('TestClass');
        $builder->addInterface('InterfaceOne');
        $builder->addInterface('InterfaceTwo');

        $this->render($builder->build())->shouldReturn(<<<EOF
class TestClass implements InterfaceOne, InterfaceTwo
{
}
EOF
        );
    }

    function it_creates_a_class_which_implements_a_namespaced_interface()
    {
        $builder = ClassDefinition::builder('TestClass');
        $builder->addInterface('Test\Namespace\TestInterface');

        $this->render($builder->build())->shouldReturn(<<<EOF
use Test\Namespace\TestInterface;

class TestClass implements TestInterface
{
}
EOF
        );
    }

    function it_creates_a_class_with_a_public_method()
    {
        $builder = ClassDefinition::builder('TestClass');
        $builder->addMethod(MethodDefinition::createPublic(
            'testMethod',
            'return "the body";'
        ));

        $this->render($builder->build())->shouldReturn(<<<EOF
class TestClass
{
    public function testMethod()
    {
        return "the body";
    }
}
EOF
        );
    }
}
