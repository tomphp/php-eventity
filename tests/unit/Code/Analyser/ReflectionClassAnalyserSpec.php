<?php
/**
 * Created by PhpStorm.
 * User: tomoram
 * Date: 07/09/15
 * Time: 21:16
 */
namespace unit\Eventity\Code\Analyser;

use Eventity\Code\Definition\ClassDefinition;
use PhpSpec\ObjectBehavior;

class ReflectionClassAnalyserSpec extends ObjectBehavior
{
    function it_is_a_class_analyser()
    {
        $this->shouldBeAnInstanceOf(\Eventity\Code\Analyser\ClassAnalyser::class);
    }

    function it_returns_a_class_definition()
    {
        $this->analyse(ReflectionClassAnalyserSpec_TestClass::class)
            ->shouldReturnAnInstanceOf(ClassDefinition::class);
    }

    function it_sets_the_name_of_the_analysed_class()
    {
        $this->analyse(ReflectionClassAnalyserSpec_TestClass::class)
            ->getFQCN()
            ->shouldReturn(ReflectionClassAnalyserSpec_TestClass::class);
    }

    function it_adds_public_methods()
    {
        $method = $this->analyse(ReflectionClassAnalyserSpec_TestClass::class)
            ->getMethods()[0];

        $method->getName()->shouldReturn('publicFn');
    }

    function it_adds_parameters_for_methods()
    {
        $method = $this->analyse(ReflectionClassAnalyserSpec_TestClass::class)
            ->getMethods()[1];

        $method->getArguments()[0]->getName()->shouldReturn('param1');
        $method->getArguments()[1]->getName()->shouldReturn('param2');
    }
}

class ReflectionClassAnalyserSpec_TestClass
{
    public function publicFn()
    {
    }

    public function publicFnWithParams($param1, $param2)
    {
    }
}
