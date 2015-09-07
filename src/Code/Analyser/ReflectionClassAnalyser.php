<?php

namespace Eventity\Code\Analyser;

use Eventity\Code\Definition;
use Eventity\Code\Definition\ClassDefinition;
use Eventity\Code\Definition\ParameterDefinition;
use ReflectionClass;

class ReflectionClassAnalyser implements ClassAnalyser
{
    public function analyse($className)
    {
        $builder = ClassDefinition::builder();
        $builder->setClassName($className);

        $reflection = new ReflectionClass($className);

        foreach ($reflection->getMethods() as $method) {
            $parameters = array_map(
                function ($parameter) {
                    return ParameterDefinition::create($parameter->getName());
                },
                $method->getParameters()
            );

            $builder->addMethod(Definition\MethodDefinition::createPublicWithParams(
                $method->getName(),
                $parameters,
                ''
            ));
        }

        return $builder->build();
    }
}
