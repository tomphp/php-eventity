<?php

namespace Eventity\Code;

final class DefaultClassCodeRenderer implements ClassCodeRenderer
{
    /**
     * @var ClassDefinition
     */
    private $definition;

    /**
     * @var string
     */
    private $code;

    /**
     * @return string
     */
    public function render(ClassDefinition $definition)
    {
        $this->definition = $definition;

        $this->code = '';

        $this->addNamespaceToCode();
        $this->addUsesToCode();
        $this->addClassNameToCode();
        $this->addBodyToCode();

        return $this->code;
    }

    private function addNamespaceToCode()
    {
        if (!$this->definition->getNamespace()) {
            return;
        }

        $this->code .= "namespace {$this->definition->getNamespace()};";
        $this->addNewlinesToCode(2);
    }

    private function addUsesToCode()
    {
        if (!$this->definition->getUses()) {
            return;
        }

        foreach ($this->definition->getUses() as $use) {
            $this->code .= "use $use;";
            $this->addNewlinesToCode(1);
        }

        $this->addNewlinesToCode(1);
    }

    private function addClassNameToCode()
    {
        $this->code .= "class {$this->definition->getClassName()}";
        $this->addParentToDefintion();
        $this->addInterfacesToDefintion();

        $this->addNewlinesToCode(1);
    }

    private function addParentToDefintion()
    {
        if (!$this->definition->getParent()) {
            return;
        }

        $this->code .= ' extends ';

        $this->code .= $this->definition->getParent();
    }

    private function addInterfacesToDefintion()
    {
        if (!$this->definition->getInterfaces()) {
            return;
        }

        $this->code .= ' implements ';

        $this->code .= implode(', ', $this->definition->getInterfaces());
    }

    private function addBodyToCode()
    {
        $this->code .= '{';
        $this->addNewlinesToCode(1);

        $this->addMethodsToCode();

        $this->code .= '}';
    }

    private function addMethodsToCode()
    {
        foreach ($this->definition->getMethods() as $method) {
            $this->code .= "    public function {$method->getName()}()";
            $this->addNewlinesToCode(1);
            $this->code .= '    {';
            $this->addNewlinesToCode(1);
            $this->code .= "        {$method->getBody()}";
            $this->addNewlinesToCode(1);
            $this->code .= '    }';
            $this->addNewlinesToCode(1);
        }
    }

    /**
     * @param int $count
     */
    private function addNewlinesToCode($count)
    {
        $this->code .= str_repeat(PHP_EOL, $count);
    }
}
