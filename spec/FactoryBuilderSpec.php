<?php

namespace spec\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\EntityFactory;

class FactoryBuilderSpec extends ObjectBehavior
{
    /**
     * @var int
     */
    private static $testCount = 1;

    function __construct()
    {
        self::$testCount++;
    }

    function it_builds_a_factory()
    {
        $this->buildFactory($this->testClass('TestEntity'))
             ->shouldReturnAnInstanceOf(EntityFactory::class);
    }

    function it_creates_the_factory_in_the_generated_factory_namespace()
    {
        $className = $this->testClass('TestEntity');

        $this->buildFactory($className)
             ->shouldReturnAnInstanceOf("Eventity\Generated\\Factory\\$className");
    }

    function it_creates_a_create_method_on_the_factory()
    {
        $this->buildFactory($this->testClass('TestEntity'))
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
