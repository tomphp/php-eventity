<?php

namespace Eventity;

use Eventity\Code\Definition\ClassDefinition;

interface WrapperBuilder
{
    /**
     * @param string $entityName
     *
     * @return \Eventity\Code\Definition\ClassDefinition
     */
    public function build($entityName);
}
