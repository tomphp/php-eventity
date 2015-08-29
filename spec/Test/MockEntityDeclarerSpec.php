<?php

namespace spec\Eventity\Test;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\Code\ClassDeclarer;
use Eventity\Code\ClassDefinition;
use Eventity\Code\FieldDefinition;
use Eventity\Code\Value;
use Eventity\Code\ArgumentDefinition;
use Eventity\Code\MethodDefinition;

class MockEntityDeclarerSpec extends ObjectBehavior
{
    const ENTITY_NAME = 'ExampleEntity';

    function let(ClassDeclarer $declarer)
    {
        $this->beConstructedWith($declarer);

        $this->setClassName(self::ENTITY_NAME);
    }

    function it_sets_the_class_name(ClassDeclarer $declarer)
    {
        $this->declareEntityClass();

        $declarer->declareClass(Argument::that(function ($definition) {
            return $definition->getFQCN() === self::ENTITY_NAME;
        }))->shouldHaveBeenCalled();
    }

    function it_providers_a_fluent_interface_to_setClassName()
    {
        $this->setClassName('name')->shouldReturn($this->getWrappedObject());
    }

    function it_adds_a_calls_field_to_the_class(ClassDeclarer $declarer)
    {
        $this->declareEntityClass();

        $field = FieldDefinition::createPrivate('calls', Value::emptyArray());

        $declarer->declareClass(Argument::that(function ($definition) use ($field) {
            return in_array($field, $definition->getFields());
        }))->shouldHaveBeenCalled();
    }

    function it_adds_a_get_class_method_to_the_class(ClassDeclarer $declarer)
    {
        $this->declareEntityClass();

        $method = MethodDefinition::createPublicWithArgs(
            'getCalls',
            [ArgumentDefinition::create('methodName')],
            'return isset($this->calls[$methodName]) ? $this->calls[$methodName] : 0;'
        );

        $declarer->declareClass(Argument::that(function ($definition) use ($method) {
            return in_array($method, $definition->getMethods());
        }))->shouldHaveBeenCalled();
    }

    function it_adds_an_action_to_the_entity(ClassDeclarer $declarer)
    {
        $this->addAction('exampleAction');

        $this->declareEntityClass();

        $method = MethodDefinition::createPublic(
            'exampleAction',
            "if (!isset(\$this->calls['exampleAction'])) \$this->calls['exampleAction'] = 0;\n"
            . "\$this->calls['exampleAction']++;"
        );

        $declarer->declareClass(Argument::that(function ($definition) use ($method) {
            return in_array($method, $definition->getMethods());
        }))->shouldHaveBeenCalled();
    }

    function it_provides_a_fluent_interface_to_addAction()
    {
        $this->addAction('name')->shouldReturn($this->getWrappedObject());
    }
}
