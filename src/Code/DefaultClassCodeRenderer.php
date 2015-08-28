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
     * @var CodeRenderer
     */
    private $codeRenderer;

    public function __construct(CodeRenderer $codeRenderer)
    {
        $this->codeRenderer = $codeRenderer;
    }

    /**
     * @return string
     */
    public function render(ClassDefinition $definition)
    {
        $this->definition = $definition;

        $this->codeRenderer->reset();

        $this->addNamespaceToCode();
        $this->addUsesToCode();
        $this->addClassNameToCode();
        $this->addBodyToCode();

        return $this->codeRenderer->render();
    }

    private function addNamespaceToCode()
    {
        if (!$this->definition->getNamespace()) {
            return;
        }

        $this->codeRenderer->addLine("namespace {$this->definition->getNamespace()};");
        $this->codeRenderer->addNewline();
    }

    private function addUsesToCode()
    {
        if (!$this->definition->getUses()) {
            return;
        }

        foreach ($this->definition->getUses() as $use) {
            $this->codeRenderer->addLine("use $use;");
        }

        $this->codeRenderer->addNewline();
    }

    private function addClassNameToCode()
    {
        $this->codeRenderer->addInline("class {$this->definition->getClassName()}");
        $this->addParentToDefintion();
        $this->addInterfacesToDefintion();

        $this->codeRenderer->addNewline();
    }

    private function addParentToDefintion()
    {
        if (!$this->definition->getParent()) {
            return;
        }

        $this->codeRenderer->addInline(' extends ');

        $this->codeRenderer->addInline($this->definition->getParent());
    }

    private function addInterfacesToDefintion()
    {
        if (!$this->definition->getInterfaces()) {
            return;
        }

        $this->codeRenderer->addInline(' implements ');

        $this->codeRenderer->addInline(implode(', ', $this->definition->getInterfaces()));
    }

    private function addBodyToCode()
    {
        $this->codeRenderer->addLine('{');

        $this->codeRenderer->indent();

        $this->addFieldsToCode();
        $this->addMethodsToCode();

        $this->codeRenderer->outdent();

        $this->codeRenderer->addLine('}');
    }

    private function addFieldsToCode()
    {
        foreach ($this->definition->getFields() as $field) {
            $this->codeRenderer->addLine("private \${$field->getName()};");
        }
    }

    private function addMethodsToCode()
    {
        foreach ($this->definition->getMethods() as $method) {
            $this->codeRenderer->addLine("public function {$method->getName()}()");
            $this->codeRenderer->addLine('{');
            $this->codeRenderer->indent();
            foreach (explode("\n", $method->getBody()) as $line) {
                $this->codeRenderer->addLine($line);
            }
            $this->codeRenderer->outdent();
            $this->codeRenderer->addLine('}');
        }
    }
}
