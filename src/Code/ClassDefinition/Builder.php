<?php

namespace Eventity\Code\ClassDefinition;

use Eventity\Code\ClassDefinition;
use Eventity\Code\MethodDefinition;

final class Builder
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $className;

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
     * @param string $fqcn
     */
    public function __construct($fqcn)
    {
        $this->setClassAndNamespace($fqcn);
    }

    /**
     * @param string $parentName
     */
    public function setParent($parentName)
    {
        $this->parent = $this->getClassNameAndAddToUses($parentName);
    }

    /**
     * @param string $interfaceName
     */
    public function addInterface($interfaceName)
    {
        $this->interfaces[] = $this->getClassNameAndAddToUses($interfaceName);
    }

    public function addMethod(MethodDefinition $method)
    {
        $this->methods[] = $method;
    }

    /**
     * @return ClassDefinition
     */
    public function build()
    {
        return new ClassDefinition(
            $this->className,
            $this->namespace,
            $this->uses,
            $this->parent,
            $this->interfaces,
            $this->methods
        );
    }

    /**
     * @param string $fqcn
     *
     * @return string
     */
    private function getClassNameAndAddToUses($fqcn)
    {
        list($namespace, $className) = $this->splitClassName($fqcn);

        if ($namespace) {
            $this->uses[] = $fqcn;
        }

        return $className;
    }

    /**
     * @param string $fqcn
     */
    private function setClassAndNamespace($fqcn)
    {
        list($this->namespace, $this->className) = $this->splitClassName($fqcn);
    }

    /**
     * @param string $fqcn
     */
    private function splitClassName($fqcn)
    {
        $parts = explode('\\', $fqcn);

        $className = array_pop($parts);

        $namespace = $parts ? implode('\\', $parts) : '';

        return [$namespace, $className];
    }
}
