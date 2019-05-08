Feature:
  A client manages their complaints

  Scenario: A user creates their new complaint
    Given I authorize with email "test@example.com" and password "1234567"

    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/service-type/list" with content:
    """
    """
    Then the response status code should be 200

    Then the JSON node "serviceTypes" should exist
    Then the JSON node "serviceTypes" should have 16 elements
    Then the JSON node "serviceTypes[0].id" should exist
    Then the JSON node "serviceTypes[0].id" should be equal to the number 1
    Then the JSON node "serviceTypes[0].title" should exist
    Then the JSON node "serviceTypes[0].createdAt" should exist
    Then the JSON node "serviceTypes[0].updatedAt" should exist


    Given I upload a complaint picture "file_1" on server
    Then the response status code should be 201
    And the JSON node "picture.id" should exist
    And the JSON node "picture.sources" should exist


    Given I create a new video with link "https://www.youtube.com/watch?v=Fw3MdwjPyHA"
    Then the response status code should be 200
    And the JSON node "video.id" should exist
    And the JSON node "video.externalId" should exist
    And the JSON node "video.metaData" should exist
    And the JSON node "video.title" should exist
    And the JSON node "video.originalLink" should exist


    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/client/complaint-tag/list?search=t" with content:
    """
    """
    Then the response status code should be 200
    And the JSON node "tags" should exist
    And the JSON node "total" should exist
    Then the JSON node "tags" should have 0 elements
    Then the JSON node "total" should be equal to the number 0

    When I create a new complaint with uploaded pictures and created videos and data:
    | message        | tags                                 | serviceType | latitude          | longitude         |
    | Test complaint | emergency, urgent, there is no water | 1           | 48.73173803702099 | 44.47196960449219 |

    Then the response status code should be 201
    And the JSON node "complaint.id" should exist
    And the JSON node "complaint.message" should exist
    And the JSON node "complaint.serviceType.id" should be equal to the number 1
    And the JSON node "complaint.tags" should exist
    And the JSON node "complaint.tags" should have 3 elements
    And the JSON node "complaint.pictures" should exist
    And the JSON node "complaint.pictures" should have 1 elements
    And the JSON node "complaint.videos" should exist
    And the JSON node "complaint.videos" should have 1 elements



  Scenario: User edit their existing complaint
    Given I authorize with email "test@example.com" and password "1234567"

    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/client/complaint/1" with content:
    """
    """
    Then the response status code should be 200
    And the JSON node "complaint.id" should exist
    And the JSON node "complaint.id" should be equal to the number 1
    When I edit my complaint "1" with data:
    | message          | tags                              | serviceType | latitude          | longitude         |
    | Edited complaint | edit complaint, new version       | 2           | 48.73173803702099 | 44.47196960449219 |

    Then the response status code should be 200
    And the JSON node "complaint.id" should exist
    And the JSON node "complaint.message" should exist
    And the JSON node "complaint.message" should be equal to the string "Edited complaint"
    And the JSON node "complaint.serviceType.id" should be equal to the number 2
    And the JSON node "complaint.tags" should exist
    And the JSON node "complaint.tags" should have 2 elements
    And the JSON node "complaint.pictures" should exist
    And the JSON node "complaint.pictures" should have 0 elements
    And the JSON node "complaint.videos" should exist
    And the JSON node "complaint.videos" should have 0 elements


  Scenario: User get a list of their complaints
    Given I authorize with email "test@example.com" and password "1234567"
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

    Given I send http request with method "GET" on relative url "/client/complaint/my/list" with content:
    """
    """
    Then the response status code should be 200
    And the JSON node "complaints" should exist
    And the JSON node "complaints" should have 1 elements
    And the JSON node "total" should exist
    And the JSON node "total" should be equal to the number 1


  Scenario: User delete their complaint
    Given I authorize with email "test@example.com" and password "1234567"
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

    Given I send http request with method "DELETE" on relative url "/client/complaint/1" with content:
    """
    """
    Then the response status code should be 200

    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"

    Given I send http request with method "GET" on relative url "/client/complaint/my/list" with content:
    """
    """
    Then the response status code should be 200
    And the JSON node "complaints" should have 0 elements
    And the JSON node "total" should be equal to the number 0



