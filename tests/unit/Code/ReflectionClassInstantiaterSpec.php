<?php

namespace unit\Eventity\Code;

use Eventity\Code\ReflectionClassInstantiater;
use PhpSpec\ObjectBehavior;

final class ReflectionClassInstantiaterSpec extends ObjectBehavior
{
    function it_creates_an_instance_of_the_class_by_name()
    {
        $this->instantiate(ReflectionClassInstantiater_SpecTestClass::class)
            ->shouldReturnAnInstanceOf(ReflectionClassInstantiater_SpecTestClass::class);
    }

    function it_creates_a_class_with_constructor_parameters()
    {
        $params = [1, 'two', 3.0, false];

        $instance = $this->instantiate(ReflectionClassInstantiater_SpecTestClass::class, $params);

        $instance->getConstructorParams()->shouldBeLike($params);
    }
}

final class ReflectionClassInstantiater_SpecTestClass
{
    /**
     * @var mixed[]
     */
    private $constructorParams;

    public function __construct()
    {
        $this->constructorParams = func_get_args();
    }

    /**
     * @return mixed[]
     */
    public function getConstructorParams()
    {
        return $this->constructorParams;
    }
}
