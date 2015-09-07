<?php

namespace unit\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\WrapperBuilder;
use Eventity\FactoryBuilder;
use Eventity\Code\Declarer\ClassDeclarer;
use Eventity\Code\Instantiater\ClassInstantiater;
use Eventity\Code\Definition\ClassDefinition;

final class EntityEnvironmentCreatorSpec extends ObjectBehavior
{
    const ENTITY_FQCN = 'NS1\TestEntity';
    const FACTORY_FQCN = 'NS2\TestFactory';
    const WRAPPER_FQCN = 'NS3\TestWrapper';

    /**
     * @var ClassDefinition
     */
    private $wrapper;

    /**
     * @var \Eventity\Code\Definition\ClassDefinition
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
            ->setClassName(self::WRAPPER_FQCN)
            ->build();

        $this->factory = ClassDefinition::builder()
            ->setClassName(self::FACTORY_FQCN)
            ->build();

        $wrapperBuilder->build(self::ENTITY_FQCN)->willReturn($this->wrapper);
        $factoryBuilder->build(self::ENTITY_FQCN, self::WRAPPER_FQCN)->willReturn($this->factory);
        $instantiater->instantiate(Argument::any())->willReturn('instance');
    }

    function it_declares_the_entity_wrapper(ClassDeclarer $declarer)
    {
        $this->declareClassesAndCreateFactory(self::ENTITY_FQCN);

        $declarer->declareClass($this->wrapper)->shouldHaveBeenCalled();
    }

    function it_declares_the_entity_factory(ClassDeclarer $declarer)
    {
        $this->declareClassesAndCreateFactory(self::ENTITY_FQCN);

        $declarer->declareClass($this->factory)->shouldHaveBeenCalled();
    }

    function it_returns_an_instance_of_the_factory(ClassInstantiater $instantiater)
    {
        $instance = 'test_factory_instance';
        $instantiater->instantiate(self::FACTORY_FQCN)->willReturn($instance);

        $this->declareClassesAndCreateFactory(self::ENTITY_FQCN)->shouldReturn($instance);
    }

    function it_only_declares_the_classes_for_an_entity_the_first_call(ClassDeclarer $declarer)
    {
        $this->declareClassesAndCreateFactory(self::ENTITY_FQCN);
        $this->declareClassesAndCreateFactory(self::ENTITY_FQCN);

        $declarer->declareClass(Argument::any())->shouldHaveBeenCalledTimes(2);
    }

    function it_only_declares_the_classes_for_seperate_entities(
        ClassDeclarer $declarer,
        WrapperBuilder $wrapperBuilder,
        FactoryBuilder $factoryBuilder
    ) {
        $wrapperBuilder->build('ADifferentEntity')->willReturn($this->wrapper);
        $factoryBuilder->build(Argument::any(), Argument::any())->willReturn($this->factory);

        $this->declareClassesAndCreateFactory(self::ENTITY_FQCN);
        $this->declareClassesAndCreateFactory('ADifferentEntity');

        $declarer->declareClass(Argument::any())->shouldHaveBeenCalledTimes(4);
    }

    function it_returns_the_same_factory_for_multiple_class(
        ClassInstantiater $instantiater
    ) {
        $instance = 'test_factory_instance';
        $instantiater->instantiate(Argument::any())->willReturn($instance);

        $this->declareClassesAndCreateFactory(self::ENTITY_FQCN);
        $this->declareClassesAndCreateFactory(self::ENTITY_FQCN)->shouldReturn($instance);

        $instantiater->instantiate(Argument::any())->shouldHaveBeenCalledTimes(1);
    }
}
