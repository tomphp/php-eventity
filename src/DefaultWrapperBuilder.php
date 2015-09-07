<?php

namespace Eventity;

use Eventity\Code\MethodDefinition;
use Eventity\Code\ClassDefinition;
use Eventity\Code\ClassDefinition\Builder;
use Eventity\Code\ClassAnalyser;
use Eventity\Code\ArgumentDefinition;
use Eventity\Event;
use Eventity\Code\Value;
use Eventity\Code\FieldDefinition;

final class DefaultWrapperBuilder implements WrapperBuilder
{
    const GENERATED_ENTITY_NAMESPACE = 'Eventity\\Generated\\Entity';

    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var ClassAnalyser
     */
    private $analyser;

    /**
     * @param ClassAnalyser $analyser
     */
    public function __construct(ClassAnalyser $analyser)
    {
        $this->analyser = $analyser;
    }

    /**
     * @param string $entityName
     *
     * @return ClassDefinition
     */
    public function build($entityName)
    {
        $this->builder = ClassDefinition::builder()
            ->setClassName(self::GENERATED_ENTITY_NAMESPACE . '\\' . $entityName . 'Wrapper')
            ->setParent($entityName)
            ->addInterface(EventEntity::class)
            ->addDependency(Event::class)
            ->addField(FieldDefinition::createPrivate('events', Value::emptyArray()))
            ->addField(FieldDefinition::createPrivate('entity'))
            ->addMethod(MethodDefinition::createPublicWithArgs(
                '__construct',
                [ArgumentDefinition::create('entity')],
                '$this->entity = $entity;' . PHP_EOL
                . "\$this->events[] = new Event('Create', '{$entityName}');"
            ))
            ->addMethod(MethodDefinition::createPublic(
                'getNewEvents',
                'return $this->events;'
            ));

        $this->addEntityActions($entityName);

        return $this->builder->build();
    }

    /**
     * @param string $entityName
     */
    private function addEntityActions($entityName)
    {
        $entityDefinition = $this->analyser->analyse($entityName);

        foreach ($entityDefinition->getMethods() as $method) {
            $body = '';
            if (strpos($method->getName(), 'get') !== 0) {
                $body .= $this->generatedEventCode($method->getName(), $entityName);
            }
            $body .= $this->callEntityCode($method->getName(), $this->getArgumentNames($method));

            $this->builder->addMethod(MethodDefinition::createPublicWithArgs(
                $method->getName(),
                $method->getArguments(),
                $body
            ));
        }
    }

    /**
     * @return string
     */
    private function getArgumentNames(MethodDefinition $method)
    {
        return array_map(function (ArgumentDefinition $argument) {
            return $argument->getName();
        }, $method->getArguments());
    }

    /**
     * @param mixed $event
     * @param mixed $entityName
     *
     * @return string
     */
    private function generatedEventCode($event, $entityName)
    {
        return "\$this->events[] = new Event('$event', '$entityName');\n";
    }

    /**
     * @param string   $methodName
     * @param string[] $arguments
     *
     * @return string
     */
    private function callEntityCode($methodName, array $arguments)
    {
        $argList = implode(', ', array_map(function ($argName) {
            return '$' . $argName;
        }, $arguments));

        return "return \$this->entity->{$methodName}($argList);";
    }
}
