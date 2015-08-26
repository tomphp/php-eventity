<?php

namespace spec\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\WrapperBuilder;
use Eventity\Code\ClassDefinition;
use Eventity\Code\ClassDeclarer;
use Eventity\FactoryBuilder;
use Eventity\Code\ClassInstantiater;
use Eventity\Eventity;

final class EventitySpec extends ObjectBehavior
{
    const ENTITY_NAME = 'TestEntity';
    const FACTORY_NAME = 'TestFactory';

    /**
     * @var ClassDefinition
     */
    private $wrapper;

    /**
     * @var ClassDefinition
     */
    private $factory;

    function let(
        WrapperBuilder $wrapperBuilder,
        FactoryBuilder $factoryBuilder,
        ClassDeclarer $declarer,
        ClassInstantiater $instantiater
    ) {
        $this->beConstructedWith(
            $wrapperBuilder,
            $factoryBuilder,
            $declarer,
            $instantiater
        );

        $this->wrapper = ClassDefinition::builder()
            ->setClassName('wrapper')
            ->build();

        $this->factory = ClassDefinition::builder()
            ->setClassName(self::FACTORY_NAME)
            ->build();

        $wrapperBuilder->build(self::ENTITY_NAME)->willReturn($this->wrapper);
        $factoryBuilder->build($this->wrapper)->willReturn($this->factory);
        $instantiater->instantiate(Argument::any())->willReturn('instance');
    }

    function it_declares_the_entity_wrapper(ClassDeclarer $declarer)
    {
        $this->getFactoryFor(self::ENTITY_NAME);

        $declarer->declareClass($this->wrapper)->shouldHaveBeenCalled();
    }

    function it_declares_the_entity_factory(ClassDeclarer $declarer)
    {
        $this->getFactoryFor(self::ENTITY_NAME);

        $declarer->declareClass($this->factory)->shouldHaveBeenCalled();
    }

    function it_returns_an_instance_of_the_factory(ClassInstantiater $instantiater)
    {
        $instance = 'test_factory_instance';
        $instantiater->instantiate(self::FACTORY_NAME)->willReturn($instance);

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
        WrapperBuilder $wrapperBuilder
    ) {
        $wrapperBuilder->build('ADifferentEntity')->willReturn($this->wrapper);

        $this->getFactoryFor(self::ENTITY_NAME);
        $this->getFactoryFor('ADifferentEntity');

        $declarer->declareClass(Argument::any())->shouldHaveBeenCalledTimes(4);
    }

    function it_returns_the_same_factory_for_multiple_class(
        ClassInstantiater $instantiater
    ) {
        $instance = 'test_factory_instance';
        $instantiater->instantiate(Argument::any())->willReturn($instance);

        $this->getFactoryFor(self::ENTITY_NAME);
        $this->getFactoryFor(self::ENTITY_NAME)->shouldReturn($instance);

        $instantiater->instantiate(Argument::any())->shouldHaveBeenCalledTimes(1);
    }
}
