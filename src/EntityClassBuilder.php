<?php

namespace Eventity;

use Eventity\ClassDefinition\ClassDefinitionBuilder;
use Eventity\ClassDefinition\MethodDefinition;
use Eventity\ClassDefinition\ClassDefinition;

/** @final */
class EntityClassBuilder
{
    const GENERATED_ENTITY_NAMESPACE = 'Eventity\\Generated\\Entity';

    /**
     * @param string $entityName
     *
     * @return ClassDefinition
     */
    public function buildEntity($entityName)
    {
        $builder = new ClassDefinitionBuilder(
            self::GENERATED_ENTITY_NAMESPACE.'\\'.$entityName.'Wrapper'
        );

        $builder->setParent($entityName);
        $builder->addInterface(EventEntity::class);

        $builder->addMethod(MethodDefinition::createPublic(
            'getNewEvents',
            "return [new \\Eventity\\Event('Create', '{$entityName}')];"
        ));

        return $builder->build();
    }
}
