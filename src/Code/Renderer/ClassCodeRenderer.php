<?php

namespace Eventity\Code\Renderer;

use Eventity\Code\Definition\ClassDefinition;

interface ClassCodeRenderer
{
    /**
     * @return string
     */
    public function render(ClassDefinition $definition);
}
