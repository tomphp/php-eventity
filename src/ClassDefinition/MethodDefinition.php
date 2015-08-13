<?php

namespace Eventity\ClassDefinition;

final class MethodDefinition
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public static function createPublic($name)
    {
        return new self($name);
    }

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
