<?php

namespace unit\Eventity\Code\Renderer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\Code\Definition\ClassDefinition;
use Eventity\Code\Definition\FieldDefinition;
use Eventity\Code\Definition\MethodDefinition;
use Eventity\Code\Renderer\ClassCodeRenderer;
use Eventity\Code\Renderer\CodeRenderer;

final class DefaultClassCodeRendererSpec extends ObjectBehavior
{
    function let(CodeRenderer $codeRenderer)
    {
        $this->beConstructedWith($codeRenderer);
    }

    function it_is_a_class_code_renderer()
    {
        $this->shouldBeAnInstanceOf(ClassCodeRenderer::class);
    }
}
