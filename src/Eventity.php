<?php

namespace Eventity;

use Eventity\Code\Definition\ClassDefinition;
use Eventity\Code\Renderer\DefaultClassCodeRenderer;
use Eventity\Code\Declarer\EvalClassDeclarer;
use Eventity\Code\Instantiater\ReflectionClassInstantiater;
use Eventity\Code\Renderer\DefaultCodeRenderer;
use Eventity\Code\Analyser\ReflectionClassAnalyser;
use Assert\Assertion;

final class Eventity
{
    /** @var Eventity */
    private static $instance;

    /**
     * @var EntityEnvironmentCreator
     */
    private $enviromentCreator;

    /**
     * @param EntityEnvironmentCreator $enviromentCreator
     */
    public function __construct(EntityEnvironmentCreator $enviromentCreator)
    {
        $this->enviromentCreator = $enviromentCreator;
    }

    /**
     * @return Eventity
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            $enviromentCreator = new EntityEnvironmentCreator(
                new DefaultWrapperBuilder(new ReflectionClassAnalyser()),
                new DefaultFactoryBuilder(),
                new EvalClassDeclarer(new DefaultClassCodeRenderer(new DefaultCodeRenderer())),
                new ReflectionClassInstantiater()
            );

            self::$instance = new self($enviromentCreator);
        }

        return self::$instance;
    }

    /**
     * @return EntityFactory
     */
    public function getFactoryFor($entityName)
    {
        return $this->enviromentCreator->declareClassesAndCreateFactory($entityName);
    }

    /**
     * @param array $events
     *
     * @return EventEntity
     */
    public function replay(array $events)
    {
        Assertion::allIsInstanceOf($events, Event::class);

        $entityName = reset($events)->getEntity();

        $factory = $this->enviromentCreator->declareClassesAndCreateFactory($entityName);

        return $factory->replay($events);
    }
}
