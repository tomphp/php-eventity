<?php

namespace Eventity;

use Eventity\Code\ClassDeclarer;
use Eventity\Code\ClassInstantiater;
use Eventity\Code\ClassDefinition;
use Eventity\Code\ClassCodeRenderer;

final class Eventity
{
    /** @var EntityClassBuilder */
    private $wrapperBuilder;

    /** @var FactoryBuilder */
    private $factoryBuilder;

    /** @var ClassDeclarer */
    private $classDeclarer;

    /** @var ClassInstantiater */
    private $classInstantiater;

    /** @var EntityFactory[] */
    private $factories = [];

    /** @var Eventity */
    private static $instance;

    /**
     * @param EntityClassBuilder $wrapperBuilder
     * @param FactoryBuilder     $factoryBuilder
     * @param ClassDeclarer      $classDeclarer
     * @param ClassInstantiater  $classInstantiater
     */
    public function __construct(
        EntityClassBuilder $wrapperBuilder,
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
     * @return Eventity
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self(
                new EntityClassBuilder(),
                new FactoryBuilder(),
                new ClassDeclarer(new ClassCodeRenderer()),
                new ClassInstantiater()
            );
        }

        return self::$instance;
    }

    /**
     * @return EntityFactory
     */
    public function getFactoryFor($entityName)
    {
        $wrapper = $this->wrapperBuilder->buildEntity($entityName);

        $factory = $this->factoryBuilder->buildFactory($wrapper);

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
