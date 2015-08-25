<?php

namespace integration\Eventity\Code;

use PHPUnit_Framework_TestCase;
use Eventity\Code\ClassDefinition\Builder;
use Eventity\Code\ClassDefinition;

final class ClassDefinitionTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function it_has_a_factory_method_for_the_builder()
    {
        $this->assertInstanceOf(Builder::class, ClassDefinition::builder('name'));
    }

    /** @test */
    function it_has_the_factory_method_for_the_builder_set_the_name()
    {
        $definition = ClassDefinition::builder('name')->build();

        $this->assertEquals('name', $definition->getClassName());
    }
}
