<?php

namespace Eventity\Code;

use Assert\Assertion;

final class ArgumentDefinition
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $name
     *
     * @return ArgumentDefinition
     */
    public static function create($name)
    {
        return new self(null, $name);
    }

    /**
     * @param string $type
     * @param string $name
     *
     * @return ArgumentDefinition
     */
    public static function createWithType($type, $name)
    {
        return new self($type, $name);
    }

    /**
     * @param string $type
     * @param string $name
     */
    private function __construct($type, $name)
    {
        Assertion::nullOrString($type);
        Assertion::string($name);

        $this->type = $type;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isTyped()
    {
        return $this->type !== null;
    }
}
