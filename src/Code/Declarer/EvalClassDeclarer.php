<?php

namespace Eventity\Code\Declarer;

use Eventity\Code\Definition\ClassDefinition;
use Eventity\Code\Renderer\ClassCodeRenderer;

final class EvalClassDeclarer implements ClassDeclarer
{
    /**
     * @var ClassCodeRenderer
     */
    private $renderer;

    public function __construct(ClassCodeRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function declareClass(ClassDefinition $definition)
    {
        eval($this->renderer->render($definition));
    }
}
