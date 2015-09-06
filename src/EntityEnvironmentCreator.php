<?php

namespace Eventity;

use Eventity\Code\ClassCodeRenderer;
use Eventity\Code\ClassDeclarer;
use Eventity\Code\ClassDefinition;
use Eventity\Code\ClassInstantiater;
use Eventity\Code\DefaultClassCodeRenderer;
use Eventity\Code\EvalClassDeclarer;
use Eventity\Code\ReflectionClassInstantiater;
use Eventity\Code\DefaultCodeRenderer;
use Eventity\Code\ReflectionClassAnalyser;

/** @final */
class EntityEnvironmentCreator
{
    /** @var WrapperBuilder */
    private $wrapperBuilder;

    /** @var FactoryBuilder */
    private $factoryBuilder;

    /** @var ClassDeclarer */
    private $classDeclarer;

    /** @var ClassInstantiater */
    private $classInstantiater;

    /** @var EntityFactory[] */
    private $factories = [];

    /**
     * @param WrapperBuilder    $wrapperBuilder
     * @param FactoryBuilder    $factoryBuilder
     * @param ClassDeclarer     $classDeclarer
     * @param ClassInstantiater $classInstantiater
     */
    public function __construct(
        WrapperBuilder $wrapperBuilder,
        FactoryBuilder $factoryBuilder,
        ClassDeclarer $classDeclarer,
        ClassInstantiater $classInstantiater
    ) {
        $this->wrapperBuilder = $wrapperBuilder;
        $this->factoryBuilder = $factoryBuilder;
        $this->classDeclarer = $classDeclarer;
        $this->classInstantiater = $classInstantiater;
    }

    /**
     * @return EntityFactory
     */
    public function declareClassesAndCreateFactory($entityName)
    {
        $wrapper = $this->wrapperBuilder->build($entityName);

        $factory = $this->factoryBuilder->build($entityName, $wrapper->getFQCN());

        if (!array_key_exists($entityName, $this->factories)) {
            $this->declareClassesForEntity($entityName, $wrapper, $factory);
        }

        return $this->factories[$entityName];
    }

    private function declareClassesForEntity(
        $entityName,
        ClassDefinition $wrapper,
        ClassDefinition $factory
    ) {
        $this->classDeclarer->declareClass($wrapper);
        $this->classDeclarer->declareClass($factory);

        $this->factories[$entityName] = $this->classInstantiater->instantiate($factory->getFQCN());
    }
}
