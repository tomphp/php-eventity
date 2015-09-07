<?php

namespace Eventity\Code;

use Assert\Assertion;
use Eventity\Code\Definition\ParameterDefinition;

final class MethodDefinition
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ParameterDefinition
     */
    private $parameters;

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
     * @param string                $name
     * @param ParameterDefinition[] $parameters
     * @param string                $body
     */
    public static function createPublicWithParams($name, array $parameters, $body = '')
    {
        return new self($name, $parameters, $body);
    }

    /**
     * @param string $name
     * @param ParameterDefinition[] $parameters
     * @param string $body
     */
    public function __construct($name, array $parameters, $body)
    {
        Assertion::string($name);
        Assertion::allIsInstanceOf($parameters, ParameterDefinition::class);
        Assertion::string($body);

        $this->name = $name;
        $this->parameters = $parameters;
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
     * @return ParameterDefinition[]
     */
    public function getArguments()
    {
        return $this->parameters;
    }
}
