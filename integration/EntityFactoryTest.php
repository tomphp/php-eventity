<?php

namespace integration\Eventity;

use PHPUnit_Framework_TestCase;
use Eventity\Eventity;
use Eventity\EventEntity;

final class EntityFactoryTest extends PHPUnit_Framework_TestCase
{
    /** @var EntityFactory */
    private $factory;

    function setUp()
    {
        $this->factory = Eventity::getInstance()->getFactoryFor(EntityFactoryTest_TestEntity::class);
    }

    /** @test */
    function it_creates_an_instance_of_the_entity()
    {
        $this->assertInstanceOf(
            EntityFactoryTest_TestEntity::class,
            $this->factory->create()
        );
    }

    /** @test */
    function it_wraps_the_created_instance_with_an_event_entity_wrapper()
    {
        $this->assertInstanceOf(
            EventEntity::class,
            $this->factory->create()
        );
    }
}

class EntityFactoryTest_TestEntity
{
}
