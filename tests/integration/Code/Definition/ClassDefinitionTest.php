<?php

namespace integration\Eventity\Code\Definition;

use PHPUnit_Framework_TestCase;
use Eventity\Code\Definition\ClassDefinition\Builder;
use Eventity\Code\Definition\ClassDefinition;

final class ClassDefinitionTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function it_has_a_factory_method_for_the_builder()
    {
        $this->assertInstanceOf(Builder::class, ClassDefinition::builder());
    }
}
