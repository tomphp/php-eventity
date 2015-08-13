<?php

namespace Eventity;

use Eventity\ClassDefinition\ClassDefinitionBuilder;
use Eventity\ClassDefinition\ClassCodeRenderer;
use Eventity\ClassDefinition\MethodDefinition;

final class FactoryBuilder
{
    const GENERATED_FACTORY_NAMESPACE = 'Eventity\\Generated\\Factory';
    const DEFAULT_CONSTRUCTOR_METHOD = 'create';

    /**
     * @param mixed $className
     *
     * @return EntityFactory
     */
    public function buildFactory($className)
    {
        $factoryName = self::GENERATED_FACTORY_NAMESPACE . "\\{$className}";

        $builder = new ClassDefinitionBuilder($factoryName);

        $builder->addInterface(EntityFactory::class);

        $builder->addMethod(MethodDefinition::createPublic(self::DEFAULT_CONSTRUCTOR_METHOD));

        $renderer = new ClassCodeRenderer();

        eval($renderer->render($builder->build()));

        return new $factoryName();
    }
}
