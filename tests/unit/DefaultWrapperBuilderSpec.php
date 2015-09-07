<?php

namespace unit\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\Code\ClassDefinition;
use Eventity\EventEntity;
use Eventity\Code\MethodDefinition;
use Eventity\Code\ClassAnalyser;
use Eventity\Code\ArgumentDefinition;
use Eventity\Code\FieldDefinition;
use Eventity\Code\Value;

final class DefaultWrapperBuilderSpec extends ObjectBehavior
{
    const NAME_SPACE = 'TestNamespace';
    const ENTITY_NAME = 'TestEntity';
    const ENTITY_FQCN = self::NAME_SPACE . '\\' . self::ENTITY_NAME;

    const TEST_ACTION = 'testAction';
    const TEST_ARG1 = 'arg1';
    const TEST_ARG2 = 'arg2';

    const TEST_GETTER = 'getSomething';

    function let(ClassAnalyser $analyser)
    {
        $this->beConstructedWith($analyser);

        $definition = ClassDefinition::builder()
            ->setClassName('EntityWrapper')
            ->addMethod(MethodDefinition::createPublicWithArgs(
                self::TEST_ACTION,
                [
                    ArgumentDefinition::create(self::TEST_ARG1),
                    ArgumentDefinition::create(self::TEST_ARG2),
                ],
                ''
            ))
            ->addMethod(MethodDefinition::createPublic(self::TEST_GETTER, ''))
            ->build();

        $analyser->analyse(self::ENTITY_FQCN)->willReturn($definition);

    }

    function it_builds_a_class_definition()
    {
        $this->build(self::ENTITY_FQCN)
            ->shouldReturnAnInstanceOf(ClassDefinition::class);
    }

    function it_sets_puts_the_class_inside_the_generated_entity_namespace()
    {
        $this->build(self::ENTITY_FQCN)
            ->getNamespace()
            ->shouldReturn('Eventity\Generated\Entity\\' . self::NAME_SPACE);
    }

    function it_sets_the_parent_to_the_original_class()
    {
        $classDefinition = $this->build(self::ENTITY_FQCN);

        $classDefinition->getParent()->shouldReturn(self::ENTITY_NAME);
        $classDefinition->getUses()->shouldContain(self::ENTITY_FQCN);
    }

    function it_adds_the_event_entity_interface_to_the_class()
    {
        $classDefinition = $this->build(self::ENTITY_FQCN);

        $classDefinition->getInterfaces()->shouldContain('EventEntity');
        $classDefinition->getUses()->shouldContain(EventEntity::class);
    }

    function it_adds_an_events_field()
    {
        $method = $this->build(self::ENTITY_FQCN)
            ->getFields()[0]
            ->shouldBeLike(FieldDefinition::createPrivate('events', Value::emptyArray()));
    }

    function it_adds_an_entity_field()
    {
        $method = $this->build(self::ENTITY_FQCN)
            ->getFields()[1]
            ->shouldBeLike(FieldDefinition::createPrivate('entity'));
    }

    function it_adds_a_constructor_method_to_the_class()
    {
        $method = $this->build(self::ENTITY_FQCN)->getMethods()[0];

        $method->getName()->shouldBe('__construct');
    }

    function it_adds_an_entity_argument_to_the_constructor()
    {
        $method = $this->build(self::ENTITY_FQCN)->getMethods()[0];

        $method->getArguments()->shouldBeLike([
            ArgumentDefinition::create('entity'),
        ]);
    }

    function it_assigns_to_the_entity_field_in_the_constructor()
    {
        $method = $this->build(self::ENTITY_FQCN)->getMethods()[0];

        $method->getBody()->shouldStartWith('$this->entity = $entity;');
    }

    function it_adds_a_getNewEvents_method_to_the_class()
    {
        $method = $this->build(self::ENTITY_FQCN)->getMethods()[1];

        $method->getName()->shouldBe('getNewEvents');
    }

    function it_makes_getNewEvents_return_the_events()
    {
        $method = $this->build(self::ENTITY_FQCN)->getMethods()[1];

        $method->getBody()->shouldReturn('return $this->events;');
    }

    function it_analyses_the_entity_class(ClassAnalyser $analyser)
    {
        $this->build(self::ENTITY_FQCN);

        $analyser->analyse(self::ENTITY_FQCN)->shouldHaveBeenCalled();
    }

    function it_adds_actions_for_each_defined_method()
    {
        $this->build(self::ENTITY_FQCN)
            ->shouldDefineMethodNamed(self::TEST_ACTION);
    }

    function it_adds_code_to_add_an_action_event()
    {
        $code = sprintf(
            '$this->events[] = new Event(\'%s\', \'%s\');',
            self::TEST_ACTION,
            self::ENTITY_FQCN
        );

        $this->build(self::ENTITY_FQCN)
            ->shouldDefineMethodWithCodeContaining(self::TEST_ACTION, $code);
    }

    function it_calls_the_entity_action_and_returns_the_result()
    {
        $args = '$' . self::TEST_ARG1 . ', $' . self::TEST_ARG2;
        $code = 'return $this->entity->' . self::TEST_ACTION . "($args);";

        $this->build(self::ENTITY_FQCN)
            ->shouldDefineMethodWithCodeContaining(self::TEST_ACTION, $code);
    }

    function it_passes_through_calls_on_getters()
    {
        $getterCode = 'return $this->entity->' . self::TEST_GETTER . "();";

        $method = $this->build(self::ENTITY_FQCN)->getMethods()[3];

        $method->getBody()->shouldStartWith($getterCode);
    }

    function getMatchers()
    {
        return [
            'defineMethodNamed' => function ($definition, $name) {
                $names = array_map(function (MethodDefinition $method) {
                    return $method->getName();
                }, $definition->getMethods());

                return in_array($name, $names);
            },
            'defineMethodWithCodeContaining' => function ($definition, $name, $code) {
                $method = null;

                foreach ($definition->getMethods() as $m) {
                    if ($m->getName() !== $name) {
                        continue;
                    }

                    $method = $m;
                    break;
                }

                if (!$method) {
                    return false;
                }

                return strpos($method->getBody(), $code) !== false;
            },
        ];
    }
}
