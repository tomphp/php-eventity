Feature: Replaying Events
  In order to recreate an entity in is correct state
  As a developer
  I want to replay a stream of events

  Scenario: Recreate an entity from an event stream
    Given there is an entity class named TestEntity
    And there is an instance of TestEntity created from the factory
    And the stream of events from the instance has been captured
    When I replay the captured event stream
    Then an instance of TestEntity should be produced
