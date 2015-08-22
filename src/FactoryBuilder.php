<?php

namespace Eventity;

use Eventity\ClassDefinition\ClassDefinitionBuilder;
use Eventity\ClassDefinition\ClassCodeRenderer;
use Eventity\ClassDefinition\MethodDefinition;
use Eventity\ClassDefinition\ClassDefinition;
use Eventity\EntityClassBuilder;
use Eventity\ClassDefinition\ClassDeclarer;
use Eventity\ClassDefinition\ClassInstantiater;

/** @final */
class FactoryBuilder
{
    const GENERATED_FACTORY_NAMESPACE = 'Eventity\\Generated\\Factory';
    const DEFAULT_CONSTRUCTOR_METHOD = 'create';

    /**
     * @return ClassDefinition
     */
    public function buildFactory(ClassDefinition $wrapperDefinition)
    {
        $factoryName = self::GENERATED_FACTORY_NAMESPACE . '\\' . $wrapperDefinition->getFQCN();

        $builder = new ClassDefinitionBuilder($factoryName);

        $builder->addInterface(EntityFactory::class);

        $builder->addMethod(MethodDefinition::createPublic(
            self::DEFAULT_CONSTRUCTOR_METHOD,
            "return new \\{$wrapperDefinition->getFQCN()}();"
        ));

        return $builder->build();
    }
}
