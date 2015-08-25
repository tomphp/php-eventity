<?php

namespace Eventity;

use Eventity\Code\MethodDefinition;
use Eventity\Code\ClassDefinition;

final class DefaultWrapperBuilder implements WrapperBuilder
{
    const GENERATED_ENTITY_NAMESPACE = 'Eventity\\Generated\\Entity';

    /**
     * @param string $entityName
     *
     * @return ClassDefinition
     */
    public function build($entityName)
    {
        $builder = ClassDefinition::builder(
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
