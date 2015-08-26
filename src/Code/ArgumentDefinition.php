<?php

namespace Eventity\Code;

use Assert\Assertion;

final class ArgumentDefinition
{
    /**
     * @param string $name
     *
     * @return ArgumentDefinition
     */
    public static function create($name)
    {
        Assertion::string($name);
    }
}
