Feature:
  A user restore their password

  Scenario: User request a new password recovery link
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "/security/recovery-request" with body:
    """
    {
        "email": "unknown@example.com"
    }
    """
    Then the response status code should be 400
    Then the JSON should be equal to:
    """
    {
        "errors": {
            "email": [
                "Пользователь с таким эл. адресом не найден!"
            ]
        }
    }
    """

    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "/security/recovery-request" with body:
    """
    {
        "email": "test@example.com"
    }
    """
    Then the response status code should be 200

    Given I have a password recovery key with email "test@example.com"
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I verify the existing password recovery key
    Then the response status code should be 200

    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I restore password with password "1234567" and passwordRepeat "1234567" with existing key
    Then the response status code should be 200

    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I verify the existing password recovery key
    Then the response status code should be 400