<?php

namespace Eventity\Code;

interface ClassInstantiater
{
    /**
     * @param string  $className
     * @param mixed[] $args
     *
     * @return object
     */
    public function instantiate($className, $args = []);
}
