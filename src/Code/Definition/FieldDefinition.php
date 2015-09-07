<?php

namespace Eventity\Code\Definition;

use Assert\Assertion;

final class FieldDefinition
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     *
     * @return FieldDefinition
     */
    public static function createPrivate($name, Value $default = null)
    {
        Assertion::string($name);

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
