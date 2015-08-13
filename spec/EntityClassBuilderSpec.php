<?php

namespace spec\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\ClassDefinition\ClassDefinition;
use Eventity\EventEntity;
use Eventity\ClassDefinition\MethodDefinition;

class EntityClassBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(EntityClassBuilderTestEntity::class);
    }

    function it_builds_a_class_definition()
    {
        $this->buildEntity()->shouldReturnAnInstanceOf(ClassDefinition::class);
    }

    function it_sets_the_inside_the_generated_entity_namespace()
    {
        $this->buildEntity()
            ->getNamespace()
            ->shouldReturn('Eventity\\Generated\\Entity\\' . __NAMESPACE__);
    }

    function it_sets_the_parent_to_the_original_class()
    {
        $classDefinition = $this->buildEntity();

        $classDefinition->getParent()->shouldReturn('EntityClassBuilderTestEntity');
        $classDefinition->getUses()->shouldContain(EntityClassBuilderTestEntity::class);
    }

    function it_adds_the_event_entity_interface_to_the_class()
    {
        $classDefinition = $this->buildEntity();

        $classDefinition->getInterfaces()->shouldContain('EventEntity');
        $classDefinition->getUses()->shouldContain(EventEntity::class);
    }

    function it_adds_a_getNewEvents_method_to_the_class()
    {
        $this->buildEntity()->getMethods()[0]->shouldBeLike(MethodDefinition::createPublic('getNewEvents'));
    }
}

class EntityClassBuilderTestEntity
{
}
