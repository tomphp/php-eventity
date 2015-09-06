<?php

namespace Eventity;

use Eventity\Code\MethodDefinition;
use Eventity\Code\ClassDefinition;
use Eventity\Code\ArgumentDefinition;

final class DefaultFactoryBuilder implements FactoryBuilder
{
    const GENERATED_FACTORY_NAMESPACE = 'Eventity\\Generated\\Factory';
    const DEFAULT_CONSTRUCTOR_METHOD = 'create';
    const REPLAY_METHOD = 'replay';

    /**
     * @return ClassDefinition
     */
    public function build($entityFQCN, $wrapperFQCN)
    {
        $factoryName = self::GENERATED_FACTORY_NAMESPACE . '\\' . $entityFQCN;

        return ClassDefinition::builder()
            ->setClassName($factoryName)
            ->addInterface(EntityFactory::class)
            ->addMethod(MethodDefinition::createPublic(
                self::DEFAULT_CONSTRUCTOR_METHOD,
                "\$entity = new \\$entityFQCN();\n"
                . "return new \\$wrapperFQCN(\$entity);"
            ))
            ->addMethod(MethodDefinition::createPublicWithArgs(
                self::REPLAY_METHOD,
                [ArgumentDefinition::createWithType('array', 'events')],
                ''
            ))
            ->build();
    }
}
