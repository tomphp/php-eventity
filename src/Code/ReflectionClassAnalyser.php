<?php

namespace Eventity\Code;

use Eventity\Code\Definition\ParameterDefinition;
use Eventity\Code\MethodDefinition;
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

            $builder->addMethod(MethodDefinition::createPublicWithParams(
                $method->getName(),
                $parameters,
                ''
            ));
        }

        return $builder->build();
    }
}
