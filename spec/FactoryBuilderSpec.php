<?php

namespace spec\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\EntityFactory;
use Eventity\Code\ClassDefinition;
use Eventity\Code\ClassDefinitionBuilder;
use Eventity\Code\ClassDeclarer;
use Eventity\Code\ClassInstantiater;

final class FactoryBuilderSpec extends ObjectBehavior
{
    const ENTITY_NAMESPACE = 'TestNamespace';
    const ENTITY_NAME      = 'TestEntity';

    /** @var ClassDefinition */
    private $factoryDefinition;

    function let()
    {
        $wrapperDefinition = (new ClassDefinitionBuilder(
            self::ENTITY_NAMESPACE . '\\' . self::ENTITY_NAME
        ))->build();

        $this->factoryDefinition = $this->buildFactory($wrapperDefinition);
    }

    function it_returns_a_class_definition()
    {
        $this->factoryDefinition->shouldBeAnInstanceOf(ClassDefinition::class);
    }

    function it_sets_the_class_name_to_the_entity_name()
    {
        $this->factoryDefinition->getClassName()->shouldReturn(self::ENTITY_NAME);
    }

    function it_sets_namespace_to_the_generated_factory_namespace()
    {
        $this->factoryDefinition
            ->getNamespace()
            ->shouldReturn('Eventity\\Generated\\Factory\\TestNamespace');
    }

    function it_sets_the_class_to_implement_entity_factory()
    {
        $this->factoryDefinition
            ->getInterfaces()
            ->shouldContain('EntityFactory');

        $this->factoryDefinition
            ->getUses()
            ->shouldContain(EntityFactory::class);
    }

    function it_adds_the_default_create_method()
    {
        $this->factoryDefinition
            ->getMethods()[0]
            ->getName()
            ->shouldReturn('create');
    }
}
