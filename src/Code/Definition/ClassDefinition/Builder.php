<?php

namespace Eventity\Code\Definition\ClassDefinition;

use Eventity\Code\Definition\ClassDefinition;
use Eventity\Code\Definition\MethodDefinition;
use Eventity\Exception\BuilderIncompleteException;
use Assert\Assertion;
use Eventity\Code\Definition\FieldDefinition;

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
     * @var FieldDefinition
     */
    private $fields = [];

    /**
     * @var \Eventity\Code\Definition\MethodDefinition[]
     */
    private $methods = [];

    /**
     * @param string $fqcn
     *
     * @return self
     */
    public function setClassName($fqcn)
    {
        Assertion::string($fqcn);

        list($this->namespace, $this->className) = $this->splitClassName($fqcn);

        return $this;
    }

    /**
     * @param string $parentName
     *
     * @return self
     */
    public function setParent($parentName)
    {
        Assertion::string($parentName);

        $this->parent = $this->getClassNameAndAddToUses($parentName);

        return $this;
    }

    /**
     * @param string $interfaceName
     *
     * @return self
     */
    public function addInterface($interfaceName)
    {
        Assertion::string($interfaceName);

        $this->interfaces[] = $this->getClassNameAndAddToUses($interfaceName);

        return $this;
    }

    /**
     * @param string $dependencyName
     *
     * @return self
     */
    public function addDependency($dependencyName)
    {
        Assertion::string($dependencyName);

        $this->uses[] = $dependencyName;

        return $this;
    }

    /**
     * @return self
     */
    public function addField(FieldDefinition $field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @return self
     */
    public function addMethod(MethodDefinition $method)
    {
        $this->methods[] = $method;

        return $this;
    }

    /**
     * @return ClassDefinition
     */
    public function build()
    {
        $this->assertBuilderIsComplete();

        return new ClassDefinition(
            $this->className,
            $this->namespace,
            $this->uses,
            $this->parent,
            $this->interfaces,
            $this->fields,
            $this->methods
        );
    }

    /**
     * @throws BuilderIncompleteException
     */
    private function assertBuilderIsComplete()
    {
        if (!$this->className) {
            throw new BuilderIncompleteException('Class name is not set');
        }
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
    private function splitClassName($fqcn)
    {
        $parts = explode('\\', $fqcn);

        $className = array_pop($parts);

        $namespace = $parts ? implode('\\', $parts) : '';

        return [$namespace, $className];
    }
}
