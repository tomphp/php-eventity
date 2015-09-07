<?php

namespace integration\Eventity\Code\Declarer;

use PHPUnit_Framework_TestCase;
use Eventity\Code\Definition\ClassDefinition;
use Eventity\Code\Declarer\EvalClassDeclarer;
use Eventity\Code\Renderer\DefaultClassCodeRenderer;
use Eventity\Code\Renderer\DefaultCodeRenderer;

class EvalClassDeclarerTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function it_declares_a_class()
    {
        $className = 'integration\generated\GeneratedClass';

        $definition = \Eventity\Code\Definition\ClassDefinition::builder()
            ->setClassName($className)
            ->build();

        $declarer = new EvalClassDeclarer(new DefaultClassCodeRenderer(new DefaultCodeRenderer()));

        $declarer->declareClass($definition);

        $this->assertTrue(class_exists($className));
    }
}
