<?php

namespace spec\Eventity\ClassDefinition;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\ClassDefinition\ClassDefinitionBuilder;
use Eventity\ClassDefinition\MethodDefinition;

class ClassCodeRendererSpec extends ObjectBehavior
{
    function it_is_creates_an_empty_class()
    {
        $builder = new ClassDefinitionBuilder('TestClass');

        $this->render($builder->build())->shouldReturn(<<<EOF
class TestClass
{
}
EOF
        );
    }

    public function it_creates_class_with_a_namespace()
    {
        $builder = new ClassDefinitionBuilder('Test\Namespace\NSClass');

        $this->render($builder->build())->shouldReturn(<<<EOF
namespace Test\Namespace;

class NSClass
{
}
EOF
        );
    }

    function it_creates_a_class_which_implements_interfaces()
    {
        $builder = new ClassDefinitionBuilder('TestClass');
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
        $builder = new ClassDefinitionBuilder('TestClass');
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
        $builder = new ClassDefinitionBuilder('TestClass');
        $builder->addMethod(MethodDefinition::createPublic('testMethod'));

        $this->render($builder->build())->shouldReturn(<<<EOF
class TestClass
{
    public function testMethod()
    {
    }
}
EOF
        );
    }
}
