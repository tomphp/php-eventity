<?php

namespace Eventity;

use Eventity\Code\ClassDefinition;

interface FactoryBuilder
{
    /**
     * @param string $entityFQCN
     * @param string $wrapperFQCN
     *
     * @return ClassDefinition
     */
    public function build($entityFQCN, $wrapperFQCN);
}
