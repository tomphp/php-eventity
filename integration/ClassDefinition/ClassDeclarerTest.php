<?php

namespace integration\Eventity\ClassDefinition;

use PHPUnit_Framework_TestCase;
use Eventity\ClassDefinition\ClassDefinitionBuilder;
use Eventity\ClassDefinition\ClassDeclarer;
use Eventity\ClassDefinition\ClassCodeRenderer;

class ClassDeclarerTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function it_declares_a_class()
    {
        $className = 'integration\generated\GeneratedClass';

        $builder = new ClassDefinitionBuilder($className);
        $definition = $builder->build();

        $declarer = new ClassDeclarer(new ClassCodeRenderer());

        $declarer->declareClass($definition);

        $this->assertTrue(class_exists($className));
    }
}
