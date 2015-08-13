<?php

namespace Eventity;

use Eventity\ClassDefinition\ClassDefinitionBuilder;
use Eventity\ClassDefinition\MethodDefinition;
use Eventity\ClassDefinition\ClassDefinition;

final class EntityClassBuilder
{
    const GENERATED_ENTITY_NAMESPACE = 'Eventity\\Generated\\Entity';

    /**
     * @var string
     */
    private $entityName;

    /**
     * @param string $entityName
     */
    public function __construct($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * @return ClassDefinition
     */
    public function buildEntity()
    {
        $builder = new ClassDefinitionBuilder(self::GENERATED_ENTITY_NAMESPACE . '\\' . $this->entityName . 'Wrapper');

        $builder->setParent($this->entityName);
        $builder->addInterface(EventEntity::class);

        $builder->addMethod(MethodDefinition::createPublic('getNewEvents'));

        return $builder->build();
    }
}
