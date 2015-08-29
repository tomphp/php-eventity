<?php

namespace Eventity\Code;

interface ClassAnalyser
{
    /**
     * @param string $className
     *
     * @return ClassDefinition
     */
    public function analyse($className);
}
