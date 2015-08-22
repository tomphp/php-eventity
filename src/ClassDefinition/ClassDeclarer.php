<?php

namespace Eventity\ClassDefinition;

/** @final */
class ClassDeclarer
{
    /**
     * @var ClassCodeRenderer
     */
    private $renderer;

    /**
     * @param ClassCodeRenderer $renderer
     */
    public function __construct(ClassCodeRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function declareClass(ClassDefinition $definition)
    {
        eval($this->renderer->render($definition));
    }
}
