<?php

namespace spec\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\ClassDefinition\ClassDefinition;
use Eventity\EventEntity;
use Eventity\ClassDefinition\MethodDefinition;

final class EntityClassBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith();
    }

    function it_builds_a_class_definition()
    {
        $this->buildEntity(EntityClassBuilderSpec_TestEntity::class)
            ->shouldReturnAnInstanceOf(ClassDefinition::class);
    }

    function it_sets_the_inside_the_generated_entity_namespace()
    {
        $this->buildEntity(EntityClassBuilderSpec_TestEntity::class)
            ->getNamespace()
            ->shouldReturn('Eventity\\Generated\\Entity\\' . __NAMESPACE__);
    }

    function it_sets_the_parent_to_the_original_class()
    {
        $classDefinition = $this->buildEntity(EntityClassBuilderSpec_TestEntity::class);

        $classDefinition->getParent()->shouldReturn('EntityClassBuilderSpec_TestEntity');
        $classDefinition->getUses()->shouldContain(EntityClassBuilderSpec_TestEntity::class);
    }

    function it_adds_the_event_entity_interface_to_the_class()
    {
        $classDefinition = $this->buildEntity(EntityClassBuilderSpec_TestEntity::class);

        $classDefinition->getInterfaces()->shouldContain('EventEntity');
        $classDefinition->getUses()->shouldContain(EventEntity::class);
    }

    function it_adds_a_getNewEvents_method_to_the_class()
    {
        $method = $this->buildEntity(EntityClassBuilderSpec_TestEntity::class)->getMethods()[0];

        $method->shouldBeAnInstanceOf(MethodDefinition::class);
        $method->getName()->shouldBe('getNewEvents');
    }
}

class EntityClassBuilderSpec_TestEntity
{
}
