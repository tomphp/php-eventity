<?php

namespace Eventity\Code\Renderer;

interface CodeRenderer
{
    /**
     * @param string $code
     */
    public function addInline($code);

    /**
     * @param string $line
     */
    public function addLine($line);

    public function indent();

    public function outdent();

    public function addNewline();

    /**
     * @return string
     */
    public function render();

    public function reset();
}
