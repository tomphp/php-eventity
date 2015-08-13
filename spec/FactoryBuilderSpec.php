<?php

namespace spec\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\EntityFactory;

class FactoryBuilderSpec extends ObjectBehavior
{
    function it_builds_a_factory()
    {
        $this->buildFactory(TestEntity1::class)
             ->shouldReturnAnInstanceOf(EntityFactory::class);
    }

    function it_creates_the_factory_in_the_generated_factory_namespace()
    {
        $this->buildFactory(TestEntity2::class)
             ->shouldReturnAnInstanceOf('Eventity\Generated\\Factory\\' . TestEntity2::class);
    }

    function it_creates_a_create_method_on_the_factory()
    {
        $this->buildFactory(TestEntity3::class)
             ->shouldHaveMethod('create');
    }

    /**
     * @return array
     */
    function getMatchers()
    {
        return [
            'haveMethod' => function($subject, $method) {
                return method_exists($subject, $method);
            },
        ];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function testClass($name)
    {
        $testCount = self::$testCount;

        return "FactoryBuilder\\Test{$testCount}\\$name";
    }
}

class TestEntity1
{
}

class TestEntity2
{
}

class TestEntity3
{
}
