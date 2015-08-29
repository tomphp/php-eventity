<?php

namespace Eventity\Code;

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
            $arguments = array_map(
                function ($parameter) {
                    return ArgumentDefinition::create($parameter->getName());
                },
                $method->getParameters()
            );

            $builder->addMethod(MethodDefinition::createPublicWithArgs(
                $method->getName(),
                $arguments,
                ''
            ));
        }

        return $builder->build();
    }
}
