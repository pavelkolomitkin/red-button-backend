Feature:
  A new user registers in system

  Scenario: User try register with errors
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

    When I send a "POST" request to "/security/register" with body:
    """
    {
        "email": "tes",
        "fullName": "Ivan Batkovich",
        "phoneNumber": "rew",
        "plainPassword":
        {
            "password": "1267",
            "passwordRepeat": "1234567"
        }
    }
    """
    Then the response status code should be 400
    Then the JSON should be equal to:
    """
    {
        "errors": {
            "email": [
                "Значение адреса электронной почты недопустимо."
            ],
            "phoneNumber": [
                "Значение не является допустимым номером телефона."
            ],
            "plainPassword": {
                "password": [
                    "Пароли должны совпадать"
                ]
            }
        }
    }
    """
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "/security/register" with body:
    """
    {
        "email": "test@example.com",
        "fullName": "Ivan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan BatkovichIvan Batkovich",
        "plainPassword":
        {
            "password": "1234567",
            "passwordRepeat": "1234567"
        }
    }
    """
    Then the response status code should be 400
    Then the JSON should be equal to:
    """
    {
        "errors": {
            "fullName": [
                "Допусимое количество символов: 255"
            ]
        }
    }
    """

  Scenario: User can register with correct input data
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    When I send a "POST" request to "/security/register" with body:
    """
    {
        "email": "test@example.com",
        "fullName": "Ivan Batkovich",
        "phoneNumber": "+79023456534",
        "plainPassword":
        {
            "password": "1234567",
            "passwordRepeat": "1234567"
        }
    }
    """
    Then the response status code should be 201
    Then the JSON node "user.id" should be equal to the number "1"
    Then the JSON node "user.email" should be equal to the string "test@example.com"
    Then the JSON node "user.fullName" should be equal to the string "Ivan Batkovich"
    Then the JSON node "user.phoneNumber" should be equal to the string "+79023456534"
    Then the JSON node "user.roles" should exist
    Then the JSON node "user.roles[1]" should be equal to the string "ROLE_CLIENT_USER"
