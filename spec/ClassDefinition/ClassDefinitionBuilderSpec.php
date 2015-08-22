<?php

namespace spec\Eventity\ClassDefinition;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\ClassDefinition\ClassDefinition;
use Eventity\ClassDefinition\MethodDefinition;

final class ClassDefinitionBuilderSpec extends ObjectBehavior
{
    const NAME_SPACE = 'Test\Space';
    const CLASS_NAME = 'TestClass';

    function let()
    {
        $this->beConstructedWith(self::NAME_SPACE . '\\' . self::CLASS_NAME);
    }

    function it_builds_a_class_definition()
    {
        $this->build()->shouldReturnAnInstanceOf(ClassDefinition::class);
    }

    function it_sets_the_class_name()
    {
        $this->build()->getClassName()->shouldReturn(self::CLASS_NAME);
    }

    public function it_sets_the_namespace()
    {
        $this->build()->getNamespace()->shouldReturn(self::NAME_SPACE);
    }

    function it_creates_a_class_which_implements_interfaces()
    {
        $this->addInterface('InterfaceOne');
        $this->addInterface('InterfaceTwo');

        $this->build()
             ->getInterfaces()
             ->shouldReturn(['InterfaceOne', 'InterfaceTwo']);
    }

    function it_adds_interface_namespaces_to_uses()
    {
        $this->addInterface('NamespaceA\InterfaceOne');
        $this->addInterface('NamespaceB\InterfaceTwo');

        $this->build()->getUses()->shouldReturn([
            'NamespaceA\InterfaceOne',
            'NamespaceB\InterfaceTwo',
        ]);
    }

    function it_builds_a_class_which_extends_its_parent()
    {
        $this->setParent('ParentClass');

        $this->build()->getParent()->shouldReturn('ParentClass');
    }

    function it_adds_parent_namespace_to_uses()
    {
        $this->setParent('NamespaceA\ParentClass');

        $this->build()->getUses()->shouldReturn(['NamespaceA\ParentClass']);
    }

    function it_adds_methods()
    {
        $method1 = MethodDefinition::createPublic('methodOne');
        $method2 = MethodDefinition::createPublic('methodTwo');

        $this->addMethod($method1);
        $this->addMethod($method2);

        $this->build()->getMethods()->shouldReturn([$method1, $method2]);
    }
}
