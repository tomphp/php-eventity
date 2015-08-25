<?php

namespace Eventity\Code;

interface ClassCodeRenderer
{
    /**
     * @return string
     */
    public function render(ClassDefinition $definition);
}
