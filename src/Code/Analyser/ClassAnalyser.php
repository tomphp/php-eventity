<?php

namespace Eventity\Code\Analyser;

use Eventity\Code\Definition\ClassDefinition;

interface ClassAnalyser
{
    /**
     * @param string $className
     *
     * @return \Eventity\Code\Definition\ClassDefinition
     */
    public function analyse($className);
}
