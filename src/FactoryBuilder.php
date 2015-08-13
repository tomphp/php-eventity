<?php

namespace Eventity;

use Eventity\ClassDefinition\ClassDefinitionBuilder;
use Eventity\ClassDefinition\ClassCodeRenderer;
use Eventity\ClassDefinition\MethodDefinition;
use Eventity\ClassDefinition\ClassDefinition;

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

        $entityDefinition = $this->createEntityClassDefinition($className);

        $this->createClassesFromDefinition([
            $entityDefinition,
            $this->createFactoryClassDefinition($factoryName, $entityDefinition)
        ]);

        return new $factoryName();
    }

    /**
     * @param string $className
     *
     * @return ClassDefinition
     */
    private function createEntityClassDefinition($className)
    {
        $builder = new EntityClassBuilder($className);

        return $builder->buildEntity();
    }
    /**
     * @param string          $factoryName
     * @param ClassDefinition $entityDefinition
     *
     * @return ClassDefinition
     */
    private function createFactoryClassDefinition(
        $factoryName,
        ClassDefinition $entityDefinition
    ) {
        $builder = new ClassDefinitionBuilder($factoryName);

        $builder->addInterface(EntityFactory::class);

        $builder->addMethod(MethodDefinition::createPublic(
            self::DEFAULT_CONSTRUCTOR_METHOD,
            'return new \\' . $entityDefinition->getNamespace() . '\\' . $entityDefinition->getClassName() . '();'
        ));

        return $builder->build();
    }

    /**
     * @param ClassDefinition[] $classes
     */
    private function createClassesFromDefinition(array $classes)
    {
        $renderer = new ClassCodeRenderer();

        foreach ($classes as $class) {
            eval($renderer->render($class));
        }
    }
}
