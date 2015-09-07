<?php

namespace Eventity\Code\Declarer;

use Eventity\Code\Definition\ClassDefinition;

interface ClassDeclarer
{
    public function declareClass(ClassDefinition $definition);
}
