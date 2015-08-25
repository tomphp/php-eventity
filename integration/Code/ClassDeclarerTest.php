<?php

namespace integration\Eventity\Code;

use PHPUnit_Framework_TestCase;
use Eventity\Code\ClassDefinitionBuilder;
use Eventity\Code\ClassDeclarer;
use Eventity\Code\ClassCodeRenderer;

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
