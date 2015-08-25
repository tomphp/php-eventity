<?php

namespace integration\Eventity\Code;

use PHPUnit_Framework_TestCase;
use Eventity\Code\ClassDefinition;
use Eventity\Code\ClassDeclarer;
use Eventity\Code\DefaultClassCodeRenderer;

class ClassDeclarerTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function it_declares_a_class()
    {
        $className = 'integration\generated\GeneratedClass';

        $builder = ClassDefinition::builder($className);
        $definition = $builder->build();

        $declarer = new ClassDeclarer(new DefaultClassCodeRenderer());

        $declarer->declareClass($definition);

        $this->assertTrue(class_exists($className));
    }
}
