<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Eventity\FactoryBuilder;
use Eventity\ClassDefinition\ClassDefinitionBuilder;
use Eventity\ClassDefinition\ClassCodeRenderer;
use Eventity\Event;

class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var int
     */
    private static $scenarioCount = 0;

    /**
     * @var FactoryBuilder
     */
    private $factoryBuilder;

    /**
     * @BeforeScenario
     */
    public function beforeScenario()
    {
        self::$scenarioCount++;

        $this->factoryBuilder = new FactoryBuilder();
    }

    /**
     * @Given there is an entity class named :className
     */
    public function thereIsAnEntityClassNamed($className)
    {
        $builder = new ClassDefinitionBuilder($this->createTestClassName($className));
        $renderer = new ClassCodeRenderer();

        eval($renderer->render($builder->build()));
    }

    /**
     * @When I create an instance of :className via the factory
     */
    public function iCreateAnInstanceOfViaTheFactory($className)
    {
        $factory = $this->factoryBuilder
                        ->buildFactory($this->createTestClassName($className));

        $this->instance = $factory->create();
    }

    /**
     * @Then the instance should have one new :eventName event
     */
    public function theInstanceShouldHaveOneNewEvent($eventName)
    {
        $eventNames = array_map(function(Event $event) {
            return $event->getName();
        }, $this->instance->getNewEvents());

        expect($eventNames)->toContain($eventName);
    }

    /**
     * @Then the event should have a new UID
     */
    public function theEventShouldHaveANewUid()
    {
        throw new PendingException();
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
}
