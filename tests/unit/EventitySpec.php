<?php

namespace unit\Eventity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Eventity\EntityEnvironmentCreator;
use Eventity\EntityFactory;
use Eventity\Event;
use Eventity\EventEntity;

final class EventitySpec extends ObjectBehavior
{
    function let(EntityEnvironmentCreator $environmentCreator)
    {
        $this->beConstructedWith($environmentCreator);
    }

    function it_returns_the_factory_for_the_entity(
        EntityEnvironmentCreator $environmentCreator,
        EntityFactory $factory
    ) {
        $environmentCreator->declareClassesAndCreateFactory('EntityName')->willReturn($factory);

        $this->getFactoryFor('EntityName')->shouldReturn($factory);
    }

    function it_gets_the_required_factory_to_replay_events(
        EntityEnvironmentCreator $environmentCreator,
        EntityFactory $factory
    ) {
        $events = [new Event('EventName', 'EntityName')];
        $environmentCreator->declareClassesAndCreateFactory(Argument::any())
            ->willReturn($factory);

        $this->replay($events);

        $environmentCreator->declareClassesAndCreateFactory('EntityName')
            ->shouldHaveBeenCalled();
    }

    function it_replays_the_events_to_the_factory_and_return_the_entity(
        EntityEnvironmentCreator $environmentCreator,
        EntityFactory $factory,
        EventEntity $entity
    ) {
        $events = [new Event('EventName', 'EntityName')];
        $environmentCreator->declareClassesAndCreateFactory(Argument::any())
            ->willReturn($factory);
        $factory->replay($events)->willReturn($entity);

        $this->replay($events)->shouldReturn($entity);
    }
}
