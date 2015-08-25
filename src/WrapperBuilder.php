<?php

namespace Eventity;

use Eventity\Code\ClassDefinition;

interface WrapperBuilder
{
    /**
     * @param string $entityName
     *
     * @return ClassDefinition
     */
    public function build($entityName);
}
