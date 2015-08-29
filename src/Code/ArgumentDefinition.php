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
     * @param string $name
     *
     * @return ArgumentDefinition
     */
    public static function create($name)
    {
        return new self($name);
    }

    /**
     * @param name $name
     */
    public function __construct($name)
    {
        Assertion::string($name);

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
