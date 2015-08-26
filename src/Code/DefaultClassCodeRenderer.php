<?php

namespace Eventity\Code;

final class DefaultClassCodeRenderer implements ClassCodeRenderer
{
    const INDENT_SIZE = 4;

    /**
     * @var ClassDefinition
     */
    private $definition;

    /**
     * @var string
     */
    private $code;

    /**
     * @var int
     */
    private $indentLevel = 0;

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

        $this->addLine("namespace {$this->definition->getNamespace()};");
        $this->addNewline();
    }

    private function addUsesToCode()
    {
        if (!$this->definition->getUses()) {
            return;
        }

        foreach ($this->definition->getUses() as $use) {
            $this->addLine("use $use;");
        }

        $this->addNewline();
    }

    private function addClassNameToCode()
    {
        $this->code .= "class {$this->definition->getClassName()}";
        $this->addParentToDefintion();
        $this->addInterfacesToDefintion();

        $this->addNewline();
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
        $this->addLine('{');

        $this->indent();

        $this->addFieldsToCode();
        $this->addMethodsToCode();

        $this->outdent();

        $this->addLine('}');
    }

    private function addFieldsToCode()
    {
        foreach ($this->definition->getFields() as $field) {
            $this->addLine("private \${$field->getName()};");
        }
    }

    private function addMethodsToCode()
    {
        foreach ($this->definition->getMethods() as $method) {
            $this->addLine("public function {$method->getName()}()");
            $this->addLine('{');
            $this->indent();
            foreach (explode("\n", $method->getBody()) as $line) {
                $this->addLine($line);
            }
            $this->outdent();
            $this->addLine('}');
        }
    }

    /**
     * @param string $line
     */
    private function addLine($line)
    {
        $numSpaces = self::INDENT_SIZE * $this->indentLevel;
        $indent = str_repeat(' ', $numSpaces);

        $this->code .=  $indent . $line;
        $this->addNewline();
    }

    private function indent()
    {
        $this->indentLevel++;
    }

    private function outdent()
    {
        $this->indentLevel--;
    }

    /**
     * @param int $count
     */
    private function addNewline()
    {
        $this->code .= PHP_EOL;
    }
}
