<?php

namespace spec\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\EntityClassBuilder;
use Eventity\Code\ClassDefinition;
use Eventity\Code\ClassDeclarer;
use Eventity\FactoryBuilder;
use Eventity\Code\ClassInstantiater;
use Eventity\Eventity;

final class EventitySpec extends ObjectBehavior
{
    const ENTITY_NAME = 'TestEntity';

    function let(
        EntityClassBuilder $wrapperBuilder,
        FactoryBuilder $factoryBuilder,
        ClassDeclarer $declarer,
        ClassInstantiater $instantiater,
        ClassDefinition $wrapper,
        ClassDefinition $factory
    ) {
        $this->beConstructedWith(
            $wrapperBuilder,
            $factoryBuilder,
            $declarer,
            $instantiater
        );

        $wrapperBuilder->buildEntity(self::ENTITY_NAME)->willReturn($wrapper);
        $factoryBuilder->buildFactory($wrapper)->willReturn($factory);
        $instantiater->instantiate(Argument::any())->willReturn('instance');
    }

    function it_declares_the_entity_wrapper(
        ClassDefinition $wrapper,
        ClassDeclarer $declarer
    ) {
        $this->getFactoryFor(self::ENTITY_NAME);

        $declarer->declareClass($wrapper)->shouldHaveBeenCalled();
    }

    function it_declares_the_entity_factory(
        ClassDefinition $factory,
        ClassDeclarer $declarer
    ) {
        $this->getFactoryFor(self::ENTITY_NAME);

        $declarer->declareClass($factory)->shouldHaveBeenCalled();
    }

    function it_returns_an_instance_of_the_factory(
        ClassDefinition $factory,
        ClassInstantiater $instantiater
    ) {
        $factoryName = 'TestFactory';
        $instance = 'test_factory_instance';
        $factory->getFQCN()->willReturn($factoryName);
        $instantiater->instantiate($factoryName)->willReturn($instance);

        $this->getFactoryFor(self::ENTITY_NAME)->shouldReturn($instance);
    }

    function it_only_declares_the_classes_for_an_entity_the_first_call(ClassDeclarer $declarer)
    {
        $this->getFactoryFor(self::ENTITY_NAME);
        $this->getFactoryFor(self::ENTITY_NAME);

        $declarer->declareClass(Argument::any())->shouldHaveBeenCalledTimes(2);
    }

    function it_only_declares_the_classes_for_seperate_entities(
        ClassDeclarer $declarer,
        EntityClassBuilder $wrapperBuilder,
        ClassDefinition $wrapper
    ) {
        $wrapperBuilder->buildEntity('ADifferentEntity')->willReturn($wrapper);

        $this->getFactoryFor(self::ENTITY_NAME);
        $this->getFactoryFor('ADifferentEntity');

        $declarer->declareClass(Argument::any())->shouldHaveBeenCalledTimes(4);
    }

    function it_returns_the_same_factory_for_multiple_class(
        ClassDefinition $factory,
        ClassInstantiater $instantiater
    ) {
        $instance = 'test_factory_instance';
        $instantiater->instantiate(Argument::any())->willReturn($instance);

        $this->getFactoryFor(self::ENTITY_NAME);
        $this->getFactoryFor(self::ENTITY_NAME)->shouldReturn($instance);

        $instantiater->instantiate(Argument::any())->shouldHaveBeenCalledTimes(1);
    }
}
