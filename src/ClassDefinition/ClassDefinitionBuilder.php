<?php

namespace Eventity\ClassDefinition;

final class ClassDefinitionBuilder
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
     * @param string $interfaceName
     */
    public function addInterface($interfaceName)
    {
        list($namespace, $interface) = $this->splitClassName($interfaceName);

        if ($namespace) {
            $this->uses[] = $interfaceName;
        }

        $this->interfaces[] = $interface;
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
            $this->interfaces,
            $this->methods
        );
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
