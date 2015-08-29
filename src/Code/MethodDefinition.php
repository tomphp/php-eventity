<?php

namespace Eventity\Code;

use Assert\Assertion;

final class MethodDefinition
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ArgumentDefinition
     */
    private $arguments;

    /**
     * @var string
     */
    private $body;

    /**
     * @param string $name
     * @param string $body
     */
    public static function createPublic($name, $body = '')
    {
        return new self($name, [], $body);
    }

    /**
     * @param string               $name
     * @param ArgumentDefinition[] $arguments
     * @param string               $body
     */
    public static function createPublicWithArgs($name, array $arguments, $body = '')
    {
        return new self($name, $arguments, $body);
    }

    /**
     * @param string $name
     * @param ArgumentDefinition[] $arguments
     * @param string $body
     */
    public function __construct($name, array $arguments, $body)
    {
        Assertion::string($name);
        Assertion::allIsInstanceOf($arguments, ArgumentDefinition::class);
        Assertion::string($body);

        $this->name = $name;
        $this->arguments = $arguments;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return ArgumentDefinition[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}
