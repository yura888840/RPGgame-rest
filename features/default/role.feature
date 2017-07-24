Feature: Roles actions

  Scenario: Get all available roles for character
    When I request "GET /role"
    Then the api response status code should be 200
    And the response should contain json:
    """
    [
        {
            "name": "Researcher",
            "health": 100,
            "strength": 50,
            "experience": 50
        },
        {
            "name": "Wizard",
            "health": 100,
            "strength": 50,
            "experience": 50
        }
    ]
    """