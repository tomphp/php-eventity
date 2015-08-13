Feature: Creating Event Entity
  In order to convert an entity to an event driven entity
  As a developer
  I want to decorate it with an event generating wrapper

  Scenario: Create a newly wrapped entity
    Given there is an entity class named TestEntity
    When I create an instance of TestEntity via the factory
    Then the instance should have one new Create event
    And the event should have a new UID
