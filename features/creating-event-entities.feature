Feature: Creating Event Entity
  In order to convert an entity to an event driven entity
  As a developer
  I want to decorate it with an event generating wrapper

  Scenario: Create a newly wrapped entity
    Given there is an entity class named TestEntity
    When I create an instance with the factory for TestEntity
    Then the instance should have one new Create event

  Scenario: Calling a wrapped method
    Given there is an entity class named TestEntity with a method called testAction
    And I have created an instance of TestEntity with the factory
    When I call "testAction" on then instance
    Then the method "testAction" should be called on the wrapped instance
    And the instance should have a new "testAction" event
