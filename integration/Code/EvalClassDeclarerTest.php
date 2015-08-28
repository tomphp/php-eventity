<?php

namespace integration\Eventity\Code;

use PHPUnit_Framework_TestCase;
use Eventity\Code\ClassDefinition;
use Eventity\Code\EvalClassDeclarer;
use Eventity\Code\DefaultClassCodeRenderer;
use Eventity\Code\DefaultCodeRenderer;

class EvalClassDeclarerTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function it_declares_a_class()
    {
        $className = 'integration\generated\GeneratedClass';

        $definition = ClassDefinition::builder()
            ->setClassName($className)
            ->build();

        $declarer = new EvalClassDeclarer(new DefaultClassCodeRenderer(new DefaultCodeRenderer()));

        $declarer->declareClass($definition);

        $this->assertTrue(class_exists($className));
    }
}
