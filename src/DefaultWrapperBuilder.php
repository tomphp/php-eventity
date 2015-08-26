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
        return ClassDefinition::builder()
            ->setClassName(self::GENERATED_ENTITY_NAMESPACE . '\\' . $entityName . 'Wrapper')
            ->setParent($entityName)
            ->addInterface(EventEntity::class)
            ->addMethod(MethodDefinition::createPublic(
                'getNewEvents',
                "return [new \\Eventity\\Event('Create', '{$entityName}')];"
            ))
            ->build();
    }
}
