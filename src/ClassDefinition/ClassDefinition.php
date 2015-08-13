<?php

namespace Eventity\ClassDefinition;

final class ClassDefinition
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string[]
     */
    private $uses = [];

    /**
     * @var string[]
     */
    private $interfaces = [];

    /**
     * @var MethodDefinition[]
     */
    private $methods = [];

    /**
     * @param string             $className
     * @param string             $namespace
     * @param string[]           $uses
     * @param string[]           $interfaces
     * @param MethodDefinition[] $methods
     */
    public function __construct(
        $className,
        $namespace,
        array $uses,
        array $interfaces,
        array $methods
    ) {
        $this->className = $className;
        $this->namespace = $namespace;
        $this->uses = $uses;
        $this->interfaces = $interfaces;
        $this->methods = $methods;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /** @return string[] */
    public function getUses()
    {
        return $this->uses;
    }

    /**
     * @return string[]
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * @return MethodDefinition[]
     */
    public function getMethods()
    {
        return $this->methods;
    }
}
