<?php

namespace Eventity\Code;

final class DefaultCodeRenderer implements CodeRenderer
{
    const INDENT_SIZE = 4;

    /**
     * @var string
     */
    private $code = '';

    /**
     * @var int
     */
    private $indentLevel = 0;

    /**
     * @param string $code
     */
    public function addInline($code)
    {
        $this->code .= $code;
    }

    /**
     * @param string $line
     */
    public function addLine($line)
    {
        $numSpaces = self::INDENT_SIZE * $this->indentLevel;
        $indent = str_repeat(' ', $numSpaces);

        if ($line) {
            $this->code .=  $indent . $line;
        }

        $this->addNewline();
    }

    public function indent()
    {
        $this->indentLevel++;
    }

    public function outdent()
    {
        $this->indentLevel--;
    }

    /**
     * @param int $count
     */
    public function addNewline()
    {
        $this->code .= PHP_EOL;
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->code;
    }

    public function reset()
    {
        $this->code = '';
    }
}
