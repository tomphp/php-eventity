<?php

namespace unit\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\EntityFactory;
use Eventity\Code\Definition\ClassDefinition;
use Eventity\Code\Declarer\ClassDeclarer;
use Eventity\Code\Instantiater\ClassInstantiater;

final class DefaultFactoryBuilderSpec extends ObjectBehavior
{
    const ENTITY_FQCN  = 'TestNamespace\TestEntity';
    const WRAPPER_FQCN = 'Generated\Wrapper\TestNamespace\TestEntity';

    /** @var ClassDefinition */
    private $factoryDefinition;

    function let()
    {
        $this->factoryDefinition = $this->build(self::ENTITY_FQCN, self::WRAPPER_FQCN);
    }

    function it_returns_a_class_definition()
    {
        $this->factoryDefinition->shouldBeAnInstanceOf(ClassDefinition::class);
    }

    function it_sets_the_class_name_to_the_entity_name()
    {
        $this->factoryDefinition->getClassName()->shouldReturn('TestEntity');
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

    function it_make_the_default_create_method_wrap_an_instance_of_the_entity()
    {
        $code = '$entity = new \\' . self::ENTITY_FQCN . "();\n";
        $code .= 'return new \\' . self::WRAPPER_FQCN . '($entity);';

        $this->factoryDefinition
            ->getMethods()[0]
            ->getBody()
            ->shouldReturn($code);
    }

    function it_adds_the_replay_method()
    {
        $this->factoryDefinition
            ->getMethods()[1]
            ->getName()
            ->shouldReturn('replay');
    }

    function it_adds_the_events_parameter_to_the_replay_method()
    {
        $this->factoryDefinition
            ->getMethods()[1]
            ->getArguments()[0]
            ->getName()
            ->shouldReturn('events');
    }
}
