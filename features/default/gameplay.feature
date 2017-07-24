Feature: Gameplay flow for level 1

  Scenario: Try to go through game without game token
    When I request "GET /scenario/current_step/some_unexisting_hash"
    Then the api response status code should be 404

  Scenario: Start the game
    When I request "POST /gameplay/start" with id that saved before as last url part
    Then the api response status code should be 200
    And the "uuid" header should exist
    Then I save uuid from response headers

  Scenario: Get character through first step of gameplay
    When I request "GET /scenario/current_step" with uuid that saved before as last url part
    Then the api response status code should be 200
    And the response should contain json:
    """
    {
        "body": "You find yourself behind mountain",
        "options": []
    }
    """

  Scenario: Get character ahead of gameplay
    When I request "POST /scenario/goahead" with uuid that saved before as last url part
    Then the api response status code should be 200
    And the response should contain json:
    """
    {
        "body": "You come closer to the mountain and see the villiage",
        "options": []
    }
    """
  Scenario: As a user I want to save game and than load it to be sure that game is saved
    When I request "POST /gameplay/save" with uuid that saved before as last url part
    Then the api response status code should be 200
    When I request "POST /scenario/goahead" with uuid that saved before as last url part
    Then the api response status code should be 200
    When I request "POST /gameplay/load" with uuid that saved before as last url part
    When I request "GET /scenario/current_step" with uuid that saved before as last url part
    Then the api response status code should be 200
    And the response should contain json:
    """
    {
        "body": "You come closer to the mountain and see the villiage",
        "options": []
    }
    """