<?php

namespace spec\Eventity\Code;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefaultCodeRendererSpec extends ObjectBehavior
{
    function it_add_code_inline()
    {
        $this->addInline('a');
        $this->addInline('b');
        $this->addInline('c');

        $this->render()->shouldReturn('abc');
    }

    function it_adds_lines()
    {
        $this->addLine('a');
        $this->addLine('b');

        $this->render()->shouldReturn("a\nb\n");
    }

    function it_adds_new_lines()
    {
        $this->addLine('a');
        $this->addNewline();
        $this->addLine('b');

        $this->render()->shouldReturn("a\n\nb\n");
    }

    function it_indents()
    {
        $this->addLine('line1');
        $this->indent();
        $this->addLine('line2');
        $this->indent();
        $this->addLine('line3');

        $this->render()->shouldReturn(<<<EOF
line1
    line2
        line3

EOF
        );
    }

    function it_outdents()
    {
        $this->indent();
        $this->indent();
        $this->addLine('line1');
        $this->outdent();
        $this->addLine('line2');
        $this->outdent();
        $this->addLine('line3');

        $this->render()->shouldReturn(<<<EOF
        line1
    line2
line3

EOF
        );
    }

    function it_does_not_indent_blank_lines()
    {
        $this->indent();
        $this->addLine('');

        $this->render()->shouldReturn("\n");
    }

    function it_can_be_reset()
    {
        $this->addInline('a');

        $this->reset();

        $this->render()->shouldReturn('');
    }
}
