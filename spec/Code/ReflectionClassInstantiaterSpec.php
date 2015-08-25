<?php

namespace spec\Eventity\Code;

use Eventity\Code\ReflectionClassInstantiater;
use PhpSpec\ObjectBehavior;

final class ReflectionClassInstantiaterSpec extends ObjectBehavior
{
    function it_creates_an_instance_of_the_class_by_name()
    {
        $this->instantiate(ReflectionClassInstantiater_SpecTestClass::class)
            ->shouldReturnAnInstanceOf(ReflectionClassInstantiater_SpecTestClass::class);
    }

    function it_creates_a_class_with_constructor_arguments()
    {
        $args = [1, 'two', 3.0, false];

        $instance = $this->instantiate(ReflectionClassInstantiater_SpecTestClass::class, $args);

        $instance->getConstructorArgs()->shouldBeLike($args);
    }
}

final class ReflectionClassInstantiater_SpecTestClass
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
