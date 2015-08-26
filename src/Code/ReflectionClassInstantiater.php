<?php

namespace Eventity\Code;

use ReflectionClass;
use Assert\Assertion;

final class ReflectionClassInstantiater implements ClassInstantiater
{
    public function instantiate($className, array $args = [])
    {
        Assertion::string($className);

        if (!$args) {
            return new $className();
        }

        $reflection = new ReflectionClass($className);

        return $reflection->newInstanceArgs($args);
    }
}
