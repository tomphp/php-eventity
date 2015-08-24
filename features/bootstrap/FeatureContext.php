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
use Eventity\ClassDefinition\ClassDeclarer;
use Eventity\Eventity;
use Eventity\ClassDefinition\MethodDefinition;

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

        (new ClassDeclarer(new ClassCodeRenderer()))->declareClass($builder->build());
    }

    /**
     * @Given there is an entity class named :className with a method called :action
     */
    public function thereIsAnEntityClassNamedTestentityWithAMethodCalled($className, $action)
    {
        $builder = new ClassDefinitionBuilder($this->createTestClassName($className));

        $builder->addMethod(MethodDefinition::createPublic(
            $action,
            "if (\$this->calls['$action']) \$this->calls['$action'] = 0;\n"
            . "\$this->calls['$action']++;"
        ));

        $builder->addProperty(Scope::publicScope(), 'calls', Value::emptyArray());

        $builder->addMethod(MethodDefinition::createPulicWithArgs(
            'getCalls',
            ['methodName'],
            'return isset($this->calls[$methodName]) ? $this->calls[$methodName] : 0;'
        ));

        (new ClassDeclarer(new ClassCodeRenderer()))->declareClass($builder->build());
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
