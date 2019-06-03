Feature:
  User confirm registration by activation key

  Scenario: Activate registration by key
    Given I have an activation key with email "test@example.com"
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Then I try activate my registration by key
    Then the response status code should be 200
    Then the JSON node "user.id" should be equal to the number "1"
    Then the JSON node "user.email" should be equal to the string "test@example.com"
    Then the JSON node "user.fullName" should be equal to the string "Ivan Batkovich"
    Then the JSON node "user.phoneNumber" should be equal to the string "+79023456534"
    Then the JSON node "user.roles" should exist
    Then the JSON node "user.roles[1]" should be equal to the string "ROLE_CLIENT_USER"

  Scenario: Try to activate registration by invalid key
    Given I have an activation key with email "test@example.com"
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Then I try activate my registration by key
    Then the response status code should be 400
    Then the JSON should be equal to:
    """
    {
        "errors": {
            "key": [
                "Ссылка недействительна!"
            ]
        }
    }
    """
