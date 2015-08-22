<?php

namespace spec\Eventity\ClassDefinition;

use Eventity\ClassDefinition\ClassInstantiater;
use PhpSpec\ObjectBehavior;

final class ClassInstantiaterSpec extends ObjectBehavior
{
    function it_creates_an_instance_of_the_class_by_name()
    {
        $this->instantiate(ClassInstantiater_SpecTestClass::class)
            ->shouldReturnAnInstanceOf(ClassInstantiater_SpecTestClass::class);
    }

    function it_creates_a_class_with_constructor_arguments()
    {
        $args = [1, 'two', 3.0, false];

        $instance = $this->instantiate(ClassInstantiater_SpecTestClass::class, $args);

        $instance->getConstructorArgs()->shouldBeLike($args);
    }
}

final class ClassInstantiater_SpecTestClass
{
    /**
     * @var mixed[]
     */
    private $constructorArgs;

    public function __construct()
    {
        $this->constructorArgs = func_get_args();
    }

    /**
     * @return mixed[]
     */
    public function getConstructorArgs()
    {
        return $this->constructorArgs;
    }
}
