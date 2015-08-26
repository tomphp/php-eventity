<?php

namespace Eventity;

use Eventity\Code\MethodDefinition;
use Eventity\Code\ClassDefinition;

final class DefaultFactoryBuilder implements FactoryBuilder
{
    const GENERATED_FACTORY_NAMESPACE = 'Eventity\\Generated\\Factory';
    const DEFAULT_CONSTRUCTOR_METHOD = 'create';

    /**
     * @return ClassDefinition
     */
    public function build(ClassDefinition $wrapperDefinition)
    {
        $factoryName = self::GENERATED_FACTORY_NAMESPACE . '\\' . $wrapperDefinition->getFQCN();

        return ClassDefinition::builder()
            ->setClassName($factoryName)
            ->addInterface(EntityFactory::class)
            ->addMethod(MethodDefinition::createPublic(
                self::DEFAULT_CONSTRUCTOR_METHOD,
                "return new \\{$wrapperDefinition->getFQCN()}();"
            ))
            ->build();
    }
}
