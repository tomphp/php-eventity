<?php

namespace Eventity;

use Eventity\Code\ClassDefinition;

interface FactoryBuilder
{
    /**
     * @return ClassDefinition
     */
    public function build(ClassDefinition $wrapperDefinition);
}
