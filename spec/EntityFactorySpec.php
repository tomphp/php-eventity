<?php

namespace spec\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\FactoryBuilder;
use Eventity\EventEntity;

class EntityFactorySpec extends ObjectBehavior
{
    /**
     * @var string
     */
    private static $factoryClass;

    function let()
    {
        $this->beAnInstanceOf($this->factoryClassName());
    }

    function it_creates_an_instance_of_the_entity()
    {
        $this->create()->shouldReturnAnInstanceOf(EntityFactoryTestEntity::class);
    }

    function it_wraps_the_created_instance_with_an_event_entity_wrapper()
    {
        $this->create()->shouldReturnAnInstanceOf(EventEntity::class);
    }

    /**
     * @return string
     */
    private function factoryClassName()
    {
        if (!self::$factoryClass) {
            $builder = new FactoryBuilder();
            $factory = $builder->buildFactory(EntityFactoryTestEntity::class);
            self::$factoryClass = get_class($factory);
        }

        return self::$factoryClass;
    }
}

class EntityFactoryTestEntity
{
}
