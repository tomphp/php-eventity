<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Eventity\Code\Declarer\EvalClassDeclarer;
use Eventity\Code\Definition\ClassDefinition;
use Eventity\Code\Renderer\DefaultClassCodeRenderer;
use Eventity\Event;
use Eventity\Eventity;
use Eventity\Code\Renderer\DefaultCodeRenderer;
use Eventity\Test\MockEntityDeclarer;
use Eventity\EventEntity;

class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var int
     */
    private static $scenarioCount = 0;

    /**
     * @var EventEntity
     */
    private $instance;

    /**
     * @var Event[]
     */
    private $events;

    /**
     * @BeforeScenario
     */
    public function beforeScenario()
    {
        self::$scenarioCount++;
    }

    /**
     * @Given there is an entity class named :className
     */
    public function thereIsAnEntityClassNamed($className)
    {
        $this->getMockEntityDeclarer()
            ->setClassName($this->createTestClassName($className))
            ->declareEntityClass();
    }

    /**
     * @Given there is an entity class named :className with a method called :action
     */
    public function thereIsAnEntityClassNamedTestentityWithAMethodCalled($className, $action)
    {
        $this->getMockEntityDeclarer()
            ->setClassName($this->createTestClassName($className))
            ->addAction($action)
            ->declareEntityClass();
    }

    /**
     * @Given I have created an instance of :className with the factory
     * @Given there is an instance of :className created from the factory
     * @When I create an instance with the factory for :className
     */
    public function iCreateAnInstanceWithTheFactoryFor($className)
    {
        $this->instance = Eventity::getInstance()
            ->getFactoryFor($this->createTestClassName($className))
            ->create();
    }

    /**
     * @Given the stream of events from the instance has been captured
     */
    public function theStreamOfEventsFromTheInstanceHasBeenCaptured()
    {
        $this->events = $this->instance->getNewEvents();
    }

    /**
     * @When I call :action on then instance
     */
    public function iCallOnThenInstance($action)
    {
        $this->instance->$action();
    }

    /**
     * @When I replay the captured event stream
     */
    public function iReplayTheCapturedEventStream()
    {
        $this->instance = Eventity::getInstance()->replay($this->events);
    }

    /**
     * @Then the instance should have one new :eventName event
     */
    public function theInstanceShouldHaveOneNewEvent($eventName)
    {
        $events = $this->instance->getNewEvents();

        expect($events)->toHaveCount(1);
        expect($events[0]->getName())->toBe($eventName);
    }

    /**
     * @Then the method :methodName should be called on the wrapped instance
     */
    public function theMethodShouldBeCalledOnTheWrappedInstance($methodName)
    {
        expect($this->instance->getCalls('testAction'))->toBe(1);
    }

    /**
     * @Then the instance should have a new :eventName event
     */
    public function theInstanceShouldHaveANewEvent($eventName)
    {
        $events = $this->instance->getNewEvents();

        $event = array_pop($events);
        expect($event->getName())->toBe($eventName);
    }

    /**
     * @Then an instance of :className should be produced
     */
    public function anInstanceOfShouldBeProduced($className)
    {
        expect($this->instance)->toBeAnInstanceOf(
            $this->createTestClassName($className)
        );
    }

    /**
     * @param string $className
     *
     * @return string
     */
    private function createTestClassName($className)
    {
        $scenarioCount = self::$scenarioCount;

        return "fixtures\Scenario{$scenarioCount}\\{$className}";
    }


    /**
     * @return MockEntityDeclarer
     */
    private function getMockEntityDeclarer()
    {
        return new MockEntityDeclarer(new EvalClassDeclarer(
            new DefaultClassCodeRenderer(new DefaultCodeRenderer())
        ));
    }
}
