<?php

namespace Eventity\Code\Instantiater;

interface ClassInstantiater
{
    /**
     * @param string  $className
     * @param mixed[] $args
     *
     * @return object
     */
    public function instantiate($className, array $args = []);
}
