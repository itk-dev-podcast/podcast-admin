@jsonld
Feature: Items
  In order to …
  As a …
  I need to be able to …

  Background:
    Given feed "test.rss" is read and all items are published

  @createSchema
  Scenario: Get all items
    When I add "Accept" header equal to "application/ld+json"
    And I send a "GET" request to "/api/items"
    Then the response status code should be 200
    And the header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
    # And print last JSON response
    And the JSON node "hydra:member" should have 1 element

  @dropSchema
  Scenario: Drop schema
