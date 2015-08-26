<?php

namespace spec\Eventity\Code;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\Code\ClassDefinition;
use Eventity\Code\FieldDefinition;
use Eventity\Code\MethodDefinition;

final class DefaultClassCodeRendererSpec extends ObjectBehavior
{
    function it_is_creates_an_empty_class()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->build();

        $this->render($definition)->shouldReturn(<<<EOF
class TestClass
{
}

EOF
        );
    }

    public function it_creates_class_with_a_namespace()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('Test\Namespace\NSClass')
            ->build();

        $this->render($definition)->shouldReturn(<<<EOF
namespace Test\Namespace;

class NSClass
{
}

EOF
        );
    }

    function it_creates_a_class_which_extends_a_namespaced_parent()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->setParent('Test\Namespace\Parent')
            ->build();

        $this->render($definition)->shouldReturn(<<<EOF
use Test\Namespace\Parent;

class TestClass extends Parent
{
}

EOF
        );
    }

    function it_creates_a_class_which_implements_interfaces()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addInterface('InterfaceOne')
            ->addInterface('InterfaceTwo')
            ->build();

        $this->render($definition)->shouldReturn(<<<EOF
class TestClass implements InterfaceOne, InterfaceTwo
{
}

EOF
        );
    }

    function it_creates_a_class_which_implements_a_namespaced_interface()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addInterface('Test\Namespace\TestInterface')
            ->build();

        $this->render($definition)->shouldReturn(<<<EOF
use Test\Namespace\TestInterface;

class TestClass implements TestInterface
{
}

EOF
        );
    }

    function it_creates_a_class_with_a_private_field()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addField(FieldDefinition::createPrivate('testField'))
            ->build();

        $this->render($definition)->shouldReturn(<<<EOF
class TestClass
{
    private \$testField;
}

EOF
        );
    }

    function it_creates_a_class_with_a_public_method()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addMethod(MethodDefinition::createPublic(
                'testMethod',
                'return "the body";'
            ))
            ->build();

        $this->render($definition)->shouldReturn(<<<EOF
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

    function it_indents_multiline_method_bodies()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addMethod(MethodDefinition::createPublic(
                'testMethod',
                "\$line1;\n\$line2;"
            ))
            ->build();

        $this->render($definition)->shouldReturn(<<<EOF
class TestClass
{
    public function testMethod()
    {
        \$line1;
        \$line2;
    }
}

EOF
        );
    }
}
