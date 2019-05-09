Feature:
  A client manages their issues

  Scenario: A user create a new issue

    # Create an base stuff of clients and their existing complaints
    Given I add a client user stuff with data:
    | email                   | password | fullName       | phoneNumber   |
    | testclient1@example.com | 123456   | Test Client 1  | +79362145421  |
    | testclient2@example.com | 123456   | Test Client 2  | +79362145423  |
    | testclient3@example.com | 123456   | Test Client 3  | +79362145425  |
    | testclient4@example.com | 123456   | Test Client 4  | +79362145426  |


    # Create a base stuff of client complaints
    Given I add a client complaint stuff with data:
    | clientEmail             | latitude           | longitude     | tags                     | serviceType            | region                | message                                                      |
    | testclient1@example.com | 48.819368292       | 44.635679696  | Water, water shut-off    | Горячее водоснабжение  | Волгоградская область | They turn off water constantly                               |
    | testclient2@example.com | 48.818231250       | 44.631640195  | water shut-off, emergency| Горячее водоснабжение  | Волгоградская область | It is too bad without water                                  |
    | testclient3@example.com | 48.821487750       | 44.627803931  | Hot water                | Горячее водоснабжение  | Волгоградская область | Please, solve the problem immediately                        |
    | testclient4@example.com | 48.802063200       | 44.605316100  | Hot water, emergency     | Холодное водоснабжение | Волгоградская область | They turn off water constantly and without any warn messages |

    Given I authorize with email "test@example.com" and password "1234567"

    # Get client profile common info - numbers and complaint signature requests
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/client/common-info" with content:
    """
    """
    Then the response status code should be 200
    Then the JSON node "complaintNumber" should exist
    Then the JSON node "complaintNumber" should be equal to the number 0
    Then the JSON node "confirmationNumber" should exist
    Then the JSON node "confirmationNumber" should be equal to the number 0
    Then the JSON node "issueNumber" should exist
    Then the JSON node "issueNumber" should be equal to the number 0
    Then the JSON node "confirmations" should exist
    Then the JSON node "confirmations" should have 0 elements


    # Loading complaint by view box
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/client/complaint/geo/search?topLeftLatitude=48.82719672640593&topLeftLongitude=44.54863746400759&bottomRightLatitude=48.79288638664653&bottomRightLongitude=44.71214492555543" with content:
    """
    """
    Then the response status code should be 200
#    And print last JSON response

    Then the JSON node "complaints" should exist
    Then the JSON node "complaints" should have 4 elements

    Then the JSON node "complaints[0].id" should exist
    Then the JSON node "complaints[0].message" should be equal to the string "They turn off water constantly and without any warn messages"
    Then the JSON node "complaints[0].serviceType.id" should be equal to the number 5

    Then the JSON node "complaints[1].id" should exist
    Then the JSON node "complaints[1].message" should be equal to the string "Please, solve the problem immediately"
    Then the JSON node "complaints[1].serviceType.id" should be equal to the number 4

    Then the JSON node "complaints[2].id" should exist
    Then the JSON node "complaints[2].message" should be equal to the string "It is too bad without water"
    Then the JSON node "complaints[2].serviceType.id" should be equal to the number 4

    Then the JSON node "complaints[3].id" should exist
    Then the JSON node "complaints[3].message" should be equal to the string "They turn off water constantly"
    Then the JSON node "complaints[3].serviceType.id" should be equal to the number 4



    # Loading tags with numbers of related complaints by view box
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/client/complaint/geo-tag/search?topLeftLatitude=48.85032836195313&topLeftLongitude=44.478008721268544&bottomRightLatitude=48.790198618068786&bottomRightLongitude=44.805023644364255" with content:
    """
    """
    Then the response status code should be 200
    Then the JSON node "tags" should exist
    Then the JSON node "tags" should have 4 elements

    Then the JSON node "tags[0].complaintNumber" should exist
    Then the JSON node "tags[0].complaintNumber" should be equal to the number 2
    Then the JSON node "tags[0].tag" should exist
    Then the JSON node "tags[0].tag.title" should be equal to the string "emergency"

    Then the JSON node "tags[1].complaintNumber" should exist
    Then the JSON node "tags[1].complaintNumber" should be equal to the number 2
    Then the JSON node "tags[1].tag" should exist
    Then the JSON node "tags[1].tag.title" should be equal to the string "Hot water"

    Then the JSON node "tags[2].complaintNumber" should exist
    Then the JSON node "tags[2].complaintNumber" should be equal to the number 2
    Then the JSON node "tags[2].tag" should exist
    Then the JSON node "tags[2].tag.title" should be equal to the string "water shut-off"

    Then the JSON node "tags[3].complaintNumber" should exist
    Then the JSON node "tags[3].complaintNumber" should be equal to the number 1
    Then the JSON node "tags[3].tag" should exist
    Then the JSON node "tags[3].tag.title" should be equal to the string "Water"


    # Loading complaints by a certain tag
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I select complaints by tags "emergency" and viewBox:
    | topLeftLatitude   | topLeftLongitude  | bottomRightLatitude | bottomRightLongitude |
    | 48.84965060911401 | 44.47732207576074 | 48.78952005168193   | 44.804336998856435   |


    Then the response status code should be 200
    Then the JSON node "complaints" should exist
    Then the JSON node "complaints" should have 2 elements

    Then the JSON node "complaints[0].id" should exist
    Then the JSON node "complaints[0].message" should be equal to the string "They turn off water constantly and without any warn messages"
    Then the JSON node "complaints[0].serviceType.id" should be equal to the number 5

    Then the JSON node "complaints[1].id" should exist
    Then the JSON node "complaints[1].message" should be equal to the string "It is too bad without water"
    Then the JSON node "complaints[1].serviceType.id" should be equal to the number 4


    # Select a certain location of the issue
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/geo/get?latitude=48.8201034046395&longitude=44.632312059402466" with content:
    """
    """

    Then the response status code should be 200
    Then the JSON node "region" should exist
    Then the JSON node "region.id" should be equal to the number 35
    Then the JSON node "addition" should exist

    And I keep the found region


    # Searching tags around the selected location
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/client/complaint/geo-tag/search?centerLatitude=48.8201034046395&centerLongitude=44.632312059402466" with content:
    """
    """

    Then the response status code should be 200
    Then the JSON node "tags" should exist
    Then the JSON node "tags" should have 4 elements

    Then the JSON node "tags[0].complaintNumber" should exist
    Then the JSON node "tags[0].complaintNumber" should be equal to the number 2
    Then the JSON node "tags[0].tag" should exist
    Then the JSON node "tags[0].tag.title" should be equal to the string "water shut-off"

    Then the JSON node "tags[1].complaintNumber" should exist
    Then the JSON node "tags[1].complaintNumber" should be equal to the number 1
    Then the JSON node "tags[1].tag" should exist
    Then the JSON node "tags[1].tag.title" should be equal to the string "emergency"

    Then the JSON node "tags[2].complaintNumber" should exist
    Then the JSON node "tags[2].complaintNumber" should be equal to the number 1
    Then the JSON node "tags[2].tag" should exist
    Then the JSON node "tags[2].tag.title" should be equal to the string "Hot water"

    Then the JSON node "tags[3].complaintNumber" should exist
    Then the JSON node "tags[3].complaintNumber" should be equal to the number 1
    Then the JSON node "tags[3].tag" should exist
    Then the JSON node "tags[3].tag.title" should be equal to the string "Water"




    # Select complaints around the selected location and certain tags
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I select complaints by tags "water shut-off" and center latitude 48.8201034046395 and center longitude 44.63231205940246

    Then the response status code should be 200
    Then the JSON node "complaints" should exist
    Then the JSON node "complaints" should have 2 elements

    Then the JSON node "complaints[0].id" should exist
    Then the JSON node "complaints[0].message" should be equal to the string "It is too bad without water"
    Then the JSON node "complaints[0].serviceType.id" should be equal to the number 4

    Then the JSON node "complaints[1].id" should exist
    Then the JSON node "complaints[1].message" should be equal to the string "They turn off water constantly"
    Then the JSON node "complaints[1].serviceType.id" should be equal to the number 4

    And I keep last found complaints



    # Searching of companies
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I search company by name "Перспектива" around kept region

    Then the response status code should be 200
    Then the JSON node "companies" should exist
    Then the JSON node "companies" should have 2 elements
    Then the JSON node "total" should exist
    Then the JSON node "total" should be equal to the number 2

    Then the JSON node "companies[0].id" should exist
    Then the JSON node "companies[0].id" should be equal to the number 13869
#    Then the JSON node "companies[0].title" should be equal to the string '"ООО "Перспектива"'
#    Then the JSON node "companies[0].fullName" should be equal to the string 'Общество с ограниченной ответственностью "Перспектива"'

    Then the JSON node "companies[1].id" should exist
    Then the JSON node "companies[1].id" should be equal to the number 13763
#    Then the JSON node "companies[1].title" should be equal to the string 'ООО "Перспектива ЖКХ"'
#    Then the JSON node "companies[1].fullName" should be equal to the string 'Общество с ограниченной ответственностью "Перспектива ЖКХ"'

    And I keep a found company with number 1


    # Loading a service type list
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


    # Uploading a picture
    Given I upload a issue picture "file_2" on server

    Then the response status code should be 201
    And the JSON node "picture.id" should exist
    And the JSON node "picture.sources" should exist


    # Grab a video from youtube
    Given I create a new video with link "https://www.youtube.com/watch?v=uXvMOT_nL6k"
    Then the response status code should be 200
    And the JSON node "video.id" should exist
    And the JSON node "video.externalId" should exist
    And the JSON node "video.metaData" should exist
    And the JSON node "video.title" should exist
    And the JSON node "video.originalLink" should exist


    # Creating a new issue
    Given I create a new issue with data:
    | message             | serviceType           | latitude          | longitude         |
    | Test issue message  | Горячее водоснабжение | 48.8201034046395  | 44.63231205940246 |

    Then the response status code should be 201
    And the JSON node "issue" should exist
    And the JSON node "issue.id" should be equal to the number 1
    And the JSON node "issue.complaintConfirmations" should have 2 elements
    And the JSON node "issue.serviceType.id" should be equal to the number 4
    And the JSON node "issue.message" should be equal to the string "Test issue message"
    And the JSON node "issue.pictures" should have 1 elements
    And the JSON node "issue.videos" should have 1 elements

    # Get client profile common info - numbers and complaint signature requests
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/client/common-info" with content:
    """
    """

#    And print last JSON response

    Then the response status code should be 200
    Then the JSON node "complaintNumber" should exist
    Then the JSON node "complaintNumber" should be equal to the number 0
    Then the JSON node "confirmationNumber" should exist
    Then the JSON node "confirmationNumber" should be equal to the number 0
    Then the JSON node "issueNumber" should exist
    Then the JSON node "issueNumber" should be equal to the number 1
    Then the JSON node "confirmations" should exist
    Then the JSON node "confirmations" should have 0 elements



  Scenario: A user confirms a requested signature

    Given I authorize with email "testclient2@example.com" and password "123456"

    # Check income confirmation requests
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/client/common-info" with content:
    """
    """
    Then the response status code should be 200
    Then the JSON node "complaintNumber" should exist
    Then the JSON node "complaintNumber" should be equal to the number 1
    Then the JSON node "confirmationNumber" should exist
    Then the JSON node "confirmationNumber" should be equal to the number 1
    Then the JSON node "issueNumber" should exist
    Then the JSON node "issueNumber" should be equal to the number 0
    Then the JSON node "confirmations" should exist
    Then the JSON node "confirmations" should have 1 elements

    And I keep last common info

    # Confirm the request
    Given I confirm the first incoming confirmation request

    # The amount of confirmation requests should be zero
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/client/common-info" with content:
    """
    """

    Then the response status code should be 200
    Then the JSON node "complaintNumber" should exist
    Then the JSON node "complaintNumber" should be equal to the number 1
    Then the JSON node "confirmationNumber" should exist
    Then the JSON node "confirmationNumber" should be equal to the number 0
    Then the JSON node "issueNumber" should exist
    Then the JSON node "issueNumber" should be equal to the number 0
    Then the JSON node "confirmations" should exist
    Then the JSON node "confirmations" should have 0 elements

    # Get details of the issue that was created above
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/client/issue/1" with content:
    """
    """

#    And print last JSON response

    Then the response status code should be 200

    # The confirmation of the user testclient2@example.com should have status "confirmed"
    And the complaint of the user "Test Client 2" should have status "confirmed"


  Scenario: A user edits their issue

    Given I authorize with email "test@example.com" and password "1234567"

    # Getting information of an existing issue
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    Given I send http request with method "GET" on relative url "/client/issue/1" with content:
    """
    """
    Then the response status code should be 200

    And I keep last issue

    # Editing the issue with id and data
    Given I edit the issue with id 1 and data:
    | message                      | serviceType            | latitude          | longitude         |
    | Test issue message - edited  | Холодное водоснабжение | 48.8201034046395  | 44.63231205940246 |

#    And print last JSON response

    # Checking the issue
    Then the response status code should be 200
    And the JSON node "issue" should exist
    And the JSON node "issue.id" should be equal to the number 1
    And the JSON node "issue.complaintConfirmations" should have 2 elements
    And the JSON node "issue.serviceType.id" should be equal to the number 5
    And the JSON node "issue.message" should be equal to the string "Test issue message - edited"
    And the JSON node "issue.pictures" should have 1 elements
    And the JSON node "issue.videos" should have 1 elements



