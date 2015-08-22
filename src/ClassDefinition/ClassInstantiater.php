<?php

namespace Eventity\ClassDefinition;

use ReflectionClass;

/** @final */
class ClassInstantiater
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
