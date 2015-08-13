<?php

namespace spec\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\EntityFactory;
use Eventity\FactoryBuilder;

class EntityFactorySpec extends ObjectBehavior
{
    /**
     * @EntityFactory
     */
    private static $factory;

    function let()
    {
        if (!self::$factory) {
            $builder = new FactoryBuilder();
            self::$factory = $builder->buildFactory(EntityFactoryTestEntity::class);
        }

        $this->setWrappedObject(self::$factory);
    }

    function it_creates_an_instance_of_the_entity()
    {
        $this->create()->shouldReturnAnInstanceOf(EntityFactoryTestEntity::class);
    }
}

class EntityFactoryTestEntity
{
}
