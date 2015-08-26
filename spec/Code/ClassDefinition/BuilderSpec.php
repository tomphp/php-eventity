<?php

namespace spec\Eventity\Code\ClassDefinition;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\Code\ClassDefinition;
use Eventity\Code\MethodDefinition;
use Eventity\Exception\BuilderIncompleteException;

final class BuilderSpec extends ObjectBehavior
{
    const NAME_SPACE = 'Test\Space';
    const CLASS_NAME = 'TestClass';

    const FQCN = self::NAME_SPACE . '\\' . self::CLASS_NAME;

    function it_throws_if_the_class_name_is_not_set()
    {
        $this->shouldThrow(BuilderIncompleteException::class)
            ->duringBuild();
    }

    function it_provides_a_fluent_interface_for_setClassName()
    {
        $this->setClassName(self::FQCN)
            ->shouldReturn($this->getWrappedObject());
    }

    function it_builds_a_class_definition()
    {
        $this->setClassName(self::FQCN);

        $this->build()->shouldReturnAnInstanceOf(ClassDefinition::class);
    }

    function it_sets_the_class_name()
    {
        $this->setClassName(self::FQCN);

        $this->build()->getClassName()->shouldReturn(self::CLASS_NAME);
    }

    public function it_sets_the_namespace()
    {
        $this->setClassName(self::FQCN);

        $this->build()->getNamespace()->shouldReturn(self::NAME_SPACE);
    }

    function it_provides_a_fluent_interface_for_addInterface()
    {
        $this->addInterface('addInterface')
            ->shouldReturn($this->getWrappedObject());
    }

    function it_creates_a_class_which_implements_interfaces()
    {
        $this->setClassName(self::FQCN);

        $this->addInterface('InterfaceOne');
        $this->addInterface('InterfaceTwo');

        $this->build()
             ->getInterfaces()
             ->shouldReturn(['InterfaceOne', 'InterfaceTwo']);
    }

    function it_adds_interface_namespaces_to_uses()
    {
        $this->setClassName(self::FQCN);

        $this->addInterface('NamespaceA\InterfaceOne');
        $this->addInterface('NamespaceB\InterfaceTwo');

        $this->build()->getUses()->shouldReturn([
            'NamespaceA\InterfaceOne',
            'NamespaceB\InterfaceTwo',
        ]);
    }

    function it_provides_a_fluent_interface_for_setParent()
    {
        $this->setParent('ParentClass')
            ->shouldReturn($this->getWrappedObject());
    }

    function it_builds_a_class_which_extends_its_parent()
    {
        $this->setClassName(self::FQCN);

        $this->setParent('ParentClass');

        $this->build()->getParent()->shouldReturn('ParentClass');
    }

    function it_adds_parent_namespace_to_uses()
    {
        $this->setClassName(self::FQCN);

        $this->setParent('NamespaceA\ParentClass');

        $this->build()->getUses()->shouldReturn(['NamespaceA\ParentClass']);
    }

    function it_provides_a_fluent_interface_for_addMethod()
    {
        $this->addMethod(MethodDefinition::createPublic('methodName'))
            ->shouldReturn($this->getWrappedObject());
    }

    function it_adds_methods()
    {
        $this->setClassName(self::FQCN);

        $method1 = MethodDefinition::createPublic('methodOne');
        $method2 = MethodDefinition::createPublic('methodTwo');

        $this->addMethod($method1);
        $this->addMethod($method2);

        $this->build()->getMethods()->shouldReturn([$method1, $method2]);
    }
}
