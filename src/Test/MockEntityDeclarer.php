<?php

namespace Eventity\Test;

use Eventity\Code\ClassDeclarer;
use Eventity\Code\ClassDefinition\Builder;
use Eventity\Code\ClassDefinition;
use Eventity\Code\FieldDefinition;
use Eventity\Code\Value;
use Eventity\Code\MethodDefinition;
use Eventity\Code\Definition\ParameterDefinition;

final class MockEntityDeclarer
{
    /**
     * @var ClassDeclarer
     */
    private $declarer;

    /**
     * @var Builder
     */
    private $builder;

    public function __construct(ClassDeclarer $declarer)
    {
        $this->declarer = $declarer;

        $this->builder = ClassDefinition::builder();

        $this->builder
            ->addField(FieldDefinition::createPrivate('calls', Value::emptyArray()))
            ->addMethod(MethodDefinition::createPublicWithParams(
                'getCalls',
                [ParameterDefinition::create('methodName')],
                'return isset($this->calls[$methodName]) ? $this->calls[$methodName] : 0;'
            ));
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setClassName($name)
    {
        $this->builder->setClassName($name);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function addAction($name)
    {
        $this->builder->addMethod(MethodDefinition::createPublic(
            $name,
            "if (!isset(\$this->calls['$name'])) \$this->calls['$name'] = 0;\n"
            . "\$this->calls['$name']++;"
        ));

        return $this;
    }

    public function declareEntityClass()
    {
        $this->declarer->declareClass($this->builder->build());
    }
}
