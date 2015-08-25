<?php

namespace Eventity\Code;

use ReflectionClass;

final class ReflectionClassInstantiater implements ClassInstantiater
{
    /**
     * @param string  $className
     * @param mixed[] $args
     *
     * @return object
     */
    public function instantiate($className, $args = [])
    {
        if (!$args) {
            return new $className();
        }

        $reflection = new ReflectionClass($className);

        return $reflection->newInstanceArgs($args);
    }
}
