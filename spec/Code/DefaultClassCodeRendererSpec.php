<?php

namespace spec\Eventity\Code;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\Code\ClassDefinition;
use Eventity\Code\FieldDefinition;
use Eventity\Code\MethodDefinition;
use Eventity\Code\ClassCodeRenderer;
use Eventity\Code\CodeRenderer;

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
