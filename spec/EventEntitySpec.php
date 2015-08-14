<?php

namespace spec\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\FactoryBuilder;
use Eventity\EventEntity;
use Eventity\Event;

class EventEntitySpec extends ObjectBehavior
{
    /**
     * @var string
     */
    private static $entityClass;

    function let()
    {
        $this->beAnInstanceOf($this->entityClassName());
    }

    function it_is_an_event_entity()
    {
        $this->shouldReturnAnInstanceOf(EventEntity::class);
    }

    function it_should_return_an_array()
    {
        $this->getNewEvents()->shouldBeArray();
    }

    function it_should_have_generated_a_create_event()
    {
        $events = $this->getNewEvents();
        $events->shouldHaveCount(1);
        $event = $events[0];
        $event->shouldBeAnInstanceOf(Event::class);
        $event->getName()->shouldReturn('Create');
    }

    function it_should_assign_the_entity_name_to_the_event()
    {
        $event = $this->getNewEvents()[0];

        $event->getEntity()->shouldReturn(EventEntityTestEntity::class);
    }

    /**
     * @return string
     */
    private function entityClassName()
    {
        if (!self::$entityClass) {
            $builder = new FactoryBuilder();
            $factory = $builder->buildFactory(EventEntityTestEntity::class);
            $entity = $factory->create();
            self::$entityClass = get_class($entity);
        }

        return self::$entityClass;
    }
}

class EventEntityTestEntity
{
}
