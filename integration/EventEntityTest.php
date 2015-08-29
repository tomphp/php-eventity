<?php

namespace integration\Eventity;

use PHPUnit_Framework_TestCase;
use Eventity\Eventity;
use Eventity\EventEntity;
use Eventity\Event;

final class EventEntityTest extends PHPUnit_Framework_TestCase
{
    /** @var EventEntity */
    private $entity;

    function setUp()
    {
        $this->entity = Eventity::getInstance()
            ->getFactoryFor(EventEntityTest_TestEntity::class)
            ->create();
    }

    /** @test */
    function it_is_an_event_entity()
    {
        $this->assertInstanceOf(EventEntity::class, $this->entity);
    }

    /** @test */
    function it_should_return_an_array_of_new_event()
    {
        $this->assertInternalType('array', $this->entity->getNewEvents());
    }

    /** @test */
    function it_should_have_generated_a_create_event()
    {
        $events = $this->entity->getNewEvents();

        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertInstanceOf(Event::class, $event);
        $this->assertEquals('Create', $event->getname());
    }

    /** @test */
    function it_should_assign_the_entity_name_to_the_event()
    {
        $event = $this->entity->getNewEvents()[0];

        $this->assertEquals(EventEntityTest_TestEntity::class, $event->getEntity());
    }
}

class EventEntityTest_TestEntity
{
}
