<?php

namespace Eventity;

use Eventity\Code\Definition\ClassDefinition;

interface FactoryBuilder
{
    /**
     * @param string $entityFQCN
     * @param string $wrapperFQCN
     *
     * @return \Eventity\Code\Definition\ClassDefinition
     */
    public function build($entityFQCN, $wrapperFQCN);
}
