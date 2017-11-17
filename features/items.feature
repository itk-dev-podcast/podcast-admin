Feature: Items
  In order to …
  As a …
  I need to be able to …

  Scenario: Anonymous access
    When I add "Accept" header equal to "application/rss+xml"
    And I send a "GET" request to "/api/items"
    Then the response status code should be 200
    And the header "Content-Type" should be equal to "application/rss+xml; charset=utf-8"
    And print last XML response
    # And the XML element "/rss/channel" should have 1 element
    And the RSS2 feed should be valid
