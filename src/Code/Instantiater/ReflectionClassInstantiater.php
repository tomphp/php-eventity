<?php

namespace Eventity\Code\Instantiater;

use Assert\Assertion;

final class ReflectionClassInstantiater implements ClassInstantiater
{
    public function instantiate($className, array $args = [])
    {
        Assertion::string($className);

        return new $className(...$args);
    }
}
