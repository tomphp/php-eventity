<?php

namespace Eventity\Code;

use Eventity\Code\ClassDefinition\Builder;

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
     * @var string
     */
    private $parent;

    /**
     * @var string[]
     */
    private $interfaces = [];

    /**
     * @var MethodDefinition[]
     */
    private $methods = [];

    /**
     * @return Builder
     */
    public static function builder()
    {
        return new Builder($name);
    }

    /**
     * @param string             $className
     * @param string             $namespace
     * @param string[]           $uses
     * @param string             $parent
     * @param string[]           $interfaces
     * @param MethodDefinition[] $methods
     */
    public function __construct(
        $className,
        $namespace,
        array $uses,
        $parent,
        array $interfaces,
        array $methods
    ) {
        $this->className = $className;
        $this->namespace = $namespace;
        $this->uses = $uses;
        $this->parent = $parent;
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

    /** @return string */
    public function getFQCN()
    {
        if (!$this->namespace) {
            return $this->className;
        }

        return $this->namespace.'\\'.$this->className;
    }

    /** @return string[] */
    public function getUses()
    {
        return $this->uses;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
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
