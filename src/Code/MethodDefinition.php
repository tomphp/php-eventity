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
     * @var string
     */
    private $body;

    /**
     * @param string $name
     * @param string $body
     */
    public static function createPublic($name, $body = '')
    {
        return new self($name, $body);
    }

    /**
     * @param string $name
     * @param string $body
     */
    public static function createPublicWithArgs($name, array $arguments, $body = '')
    {
        return new self($name, $body);
    }

    /**
     * @param string $name
     * @param string $body
     */
    public function __construct($name, $body)
    {
        Assertion::string($name);
        Assertion::string($body);

        $this->name = $name;
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
}
