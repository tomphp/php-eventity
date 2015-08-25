<?php

namespace spec\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\Code\ClassDefinition;
use Eventity\EventEntity;
use Eventity\Code\MethodDefinition;

final class WrapperBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith();
    }

    function it_builds_a_class_definition()
    {
        $this->build(WrapperBuilderSpec_TestEntity::class)
            ->shouldReturnAnInstanceOf(ClassDefinition::class);
    }

    function it_sets_the_inside_the_generated_entity_namespace()
    {
        $this->build(WrapperBuilderSpec_TestEntity::class)
            ->getNamespace()
            ->shouldReturn('Eventity\\Generated\\Entity\\' . __NAMESPACE__);
    }

    function it_sets_the_parent_to_the_original_class()
    {
        $classDefinition = $this->build(WrapperBuilderSpec_TestEntity::class);

        $classDefinition->getParent()->shouldReturn('WrapperBuilderSpec_TestEntity');
        $classDefinition->getUses()->shouldContain(WrapperBuilderSpec_TestEntity::class);
    }

    function it_adds_the_event_entity_interface_to_the_class()
    {
        $classDefinition = $this->build(WrapperBuilderSpec_TestEntity::class);

        $classDefinition->getInterfaces()->shouldContain('EventEntity');
        $classDefinition->getUses()->shouldContain(EventEntity::class);
    }

    function it_adds_a_getNewEvents_method_to_the_class()
    {
        $method = $this->build(WrapperBuilderSpec_TestEntity::class)->getMethods()[0];

        $method->shouldBeAnInstanceOf(MethodDefinition::class);
        $method->getName()->shouldBe('getNewEvents');
    }
}

class WrapperBuilderSpec_TestEntity
{
}
