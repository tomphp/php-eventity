<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Eventity\Code\EvalClassDeclarer;
use Eventity\Code\ClassDefinition;
use Eventity\Code\DefaultClassCodeRenderer;
use Eventity\Code\MethodDefinition;
use Eventity\Event;
use Eventity\Eventity;
use Eventity\FactoryBuilder;

class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var int
     */
    private static $scenarioCount = 0;

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
        $entityDefinition = ClassDefinition::builder()
            ->setClassName($this->createTestClassName($className))
            ->build();

        (new EvalClassDeclarer(new DefaultClassCodeRenderer()))->declareClass($entityDefinition);
    }

    /**
     * @Given there is an entity class named :className with a method called :action
     */
    public function thereIsAnEntityClassNamedTestentityWithAMethodCalled($className, $action)
    {
        $entityDefinition = ClassDefinition::builder()
            ->setClassName($this->createTestClassName($className))

            ->addMethod(MethodDefinition::createPublic(
                $action,
                "if (\$this->calls['$action']) \$this->calls['$action'] = 0;\n"
                . "\$this->calls['$action']++;"
            ))

            ->addProperty(Scope::publicScope(), 'calls', Value::emptyArray())
            ->addMethod(MethodDefinition::createPulicWithArgs(
                'getCalls',
                ['methodName'],
                'return isset($this->calls[$methodName]) ? $this->calls[$methodName] : 0;'
            ));

        (new EvalClassDeclarer(new DefaultClassCodeRenderer()))->declareClass($entityDefinition);
    }

    /**
     * @Given I have created an instance of :className with the factory
     * @When I create an instance with the factory for :className
     */
    public function iCreateAnInstanceWithTheFactoryFor($className)
    {
        $this->instance = Eventity::getInstance()
            ->getFactoryFor($this->createTestClassName($className))
            ->create();
    }

    /**
     * @When I call :action on then instance
     */
    public function iCallOnThenInstance($action)
    {
        $this->instance->$action();
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
     * @Then the method :arg1 should be called on the wrapped instance
     */
    public function theMethodShouldBeCalledOnTheWrappedInstance($arg1)
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
