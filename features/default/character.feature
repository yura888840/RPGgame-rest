Feature: Character actions

  Scenario: Register new character- person, with predefined role of Researcher and check if it created
    Given I have the payload
    """
    {
      "name": "TestName1",
      "role" : "Researcher",
      "uuid": "Researcher"
    }
    """
    When I request "POST /person"
    Then the api response status code should be 201
    And the "id" header should exist
    Then I save id from response headers
    When I request "GET /person" with id from response headers as last url part
    Then the api response status code should be 200
    And the response should contain json:
    """
    {
        "name": "TestName1",
        "role": "Researcher",
        "uuid": "Researcher"
    }
    """