<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\MinkContext;
use PHPUnit\Framework\Assert as Assertions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Entity\ClientUser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use \Doctrine\ORM\EntityManagerInterface;

/**
 * This context class contains the definitions of the steps used by the demo 
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 * 
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext extends MinkContext
{
    /**
     * @var KernelInterface
     */
    private $kernel;
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Symfony\Component\BrowserKit\Response|null
     */
    private $response;

    /**
     * @var string
     */
    private $authToken = null;

    /**
     * @var string
     */
    private $registerConfirmationKey;

    /**
     * @var array [fileName => pictureId]
     */
    private $uploadedComplaintPictures = [];

    /**
     * @var array [fileName => pictureId]
     */
    private $uploadedIssuePictures = [];

    /**
     * @var ClientUser
     */
    private $lastCreatedClientUser = null;

    /**
     * @var array array of video ids
     */
    private $videos = [];

    /**
     * @var array
     */
    private $keptComplaints = [];

    /**
     * @var mixed
     */
    private $keptRegion = null;

    private $keptCompany = null;

    private $keptClientCommonInfo = null;

    public function __construct(
        KernelInterface $kernel,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->kernel = $kernel;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param \Behat\Gherkin\Node\TableNode $data
     * @Given I add a client user stuff with data:
     */
    public function createClientUserStuff(\Behat\Gherkin\Node\TableNode $data)
    {
        $rows = $data->getHash();
        foreach ($rows as $row)
        {
            $this->createClientUser($row['email'], $row['password'], $row['fullName'], $row['phoneNumber']);
        }
    }

    /**
     * @param $email
     * @param $password
     * @param $fullName
     * @param $phoneNumber
     * @return ClientUser
     *
     * @Given I add new client user to the database with email :email, password :password, fullName :fullName, phoneNumber :phoneNumber
     * @throws \libphonenumber\NumberParseException
     */
    public function createClientUser($email, $password, $fullName, $phoneNumber)
    {
        $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $phone = $phoneNumberUtil->parse($phoneNumber);

        $result = new ClientUser();
        $result
            ->setPhoneNumber($phone)
            ->setEmail($email)
            ->setFullName($fullName)
            ->setIsActive(true);

        $passwordHash = $this->passwordEncoder->encodePassword($result, $password);
        $result->setPassword($passwordHash);

        $this->entityManager->persist($result);
        $this->entityManager->flush($result);

        $this->lastCreatedClientUser = $result;

        return $result;
    }

    /**
     * @param \Behat\Gherkin\Node\TableNode $data
     *
     * @Given I add a client complaint stuff with data:
     * @throws Exception
     */
    public function addClientComplaintStuff(\Behat\Gherkin\Node\TableNode $data)
    {
        $rows = $data->getHash();

        $startTime = new \DateTime('-1 month');
        $startTime->setTimestamp($startTime->getTimestamp() + 3600);

        foreach ($rows as $row)
        {
            $createdAt = clone $startTime;

            $this->addClientComplaint(
                $row['clientEmail'],
                $row['latitude'],
                $row['longitude'],
                $row['tags'],
                $row['serviceType'],
                $row['region'],
                $row['message'],
                $createdAt
            );

            $startTime->setTimestamp($startTime->getTimestamp() + 1);
        }
    }

    private function getTagNames($tagNameString, $separator = ',')
    {
        $result = explode($separator, $tagNameString);
        $result = array_map(function($item) {
            return trim($item);
        }, $result);
        $result = array_filter($result, function($item) {
            return !empty($item);
        });
        $result = array_unique($result);

        return $result;
    }

    private function getTagsByNames(array $names)
    {
        $result = $this->entityManager->getRepository('App\Entity\ComplaintTag')->createQueryBuilder('complaint_tag')
            ->where('complaint_tag.title in (:tags)')
            ->setParameter('tags', $names)
            ->getQuery()
            ->getResult();

        return $result;
    }

    private function getServiceTypeByTitle($title)
    {
        return $this->entityManager->getRepository('App\Entity\ServiceType')->findOneBy([
            'title' => trim($title)
        ]);
    }

    /**
     * @param $clientEmail
     * @param $latitude
     * @param $tags
     * @param $longitude
     * @param $serviceType
     * @param $region
     * @param $message
     * @param \DateTime $createdAt
     *
     * @Given I add a new client complaint of client :clientEmail, latitude :latitude, longitude :longitude, tags :tags, serviceType :serviceType, region :region, message :message
     * @return \App\Entity\Complaint
     */
    public function addClientComplaint($clientEmail, $latitude, $longitude, $tags, $serviceType, $region, $message, \DateTime $createdAt)
    {
        $user = $this->entityManager->getRepository('App\Entity\ClientUser')->findOneBy(['email' => $clientEmail]);
        $serviceTypeEntity = $this->entityManager->getRepository('App\Entity\ServiceType')->findOneBy(['title' => $serviceType]);

        $tags = $this->getTagNames($tags);

        /** @var array $tagEntities */
        $tagEntities = $this->getTagsByNames($tags);

        $newTags = array_filter($tags, function ($item) use ($tagEntities) {

            foreach ($tagEntities as $tagEntity)
            {
                if ($tagEntity->getTitle() === $item)
                {
                    return false;
                }
            }

            return true;
        });


        foreach ($newTags as $newTag)
        {
            $newTagEntity = new \App\Entity\ComplaintTag();
            $newTagEntity->setTitle($newTag);

            $this->entityManager->persist($newTagEntity);
            $this->entityManager->flush($newTagEntity);

            $tagEntities[] = $newTagEntity;
        }

        $regionEntity = $this->entityManager->getRepository('App\Entity\Region')->findOneBy(['title' => $region]);

        $result = new \App\Entity\Complaint();
        $result
            ->setClient($user)
            ->setServiceType($serviceTypeEntity)
            ->setTags($tagEntities)
            ->setRegion($regionEntity)
            ->setMessage(trim($message))
            ->setCreatedAt($createdAt)
        ;

        $address = new \App\Entity\OSMAddress();
        $address
            ->setLatitude($latitude)
            ->setLongitude($longitude);

        $result->setAddress($address);

        $this->entityManager->persist($result);
        $this->entityManager->flush($result);

        return $result;
    }


    /**
     * @param $tagNames
     *
     * @Given I select complaints by tags :tagNames and viewBox:
     */
    public function iSelectComplaintsByTagsInAViewBox($tagNames, \Behat\Gherkin\Node\TableNode $viewBox)
    {
        $tagNames = $this->getTagNames($tagNames);
        $tags = $this->getTagsByNames($tagNames);

        $tagIds = array_map(function ($tag) {
            return $tag->getId();
        }, $tags);
        $tagIds = implode(',', $tagIds);

        // http://localhost:7777/api/client/complaint/geo/search?tags=23&topLeftLatitude=48.84965060911401&topLeftLongitude=44.47732207576074&bottomRightLatitude=48.78952005168193&bottomRightLongitude=44.804336998856435

        $box = $viewBox->getHash()[0];

        $topLeftLatitude = $box['topLeftLatitude'];
        $topLeftLongitude = $box['topLeftLongitude'];
        $bottomRightLatitude = $box['bottomRightLatitude'];
        $bottomRightLongitude = $box['bottomRightLongitude'];

        $url = '/client/complaint/geo/search?tags=' . $tagIds . '&topLeftLatitude=' . $topLeftLatitude
            . '&topLeftLongitude=' . $topLeftLongitude
            . '&bottomRightLatitude=' . $bottomRightLatitude
            . '&bottomRightLongitude=' . $bottomRightLongitude;

        $this->sendRequest('GET', $url);
    }

    /**
     * @Then I keep last found complaints
     */
    public function iKeepLastFoundComplaints()
    {
        $data = json_decode($this->response->getContent(), true);
        $this->keptComplaints = $data['complaints'];
    }

    /**
     * @Then I keep the found region
     */
    public function iKeepFoundRegion()
    {
        $data = json_decode($this->response->getContent(), true);
        $this->keptRegion = $data['region'];
    }

    /**
     * @param $number
     * @Then I keep a found company with number :number
     */
    public function iKeepFoundCompanyWithNumber($number)
    {
        $data = json_decode($this->response->getContent(), true);
        $this->keptCompany = $data['companies'][$number];
    }

    /**
     * @Then I keep last common info
     */
    public function iKeepLastCommonClientInfo()
    {
        $data = json_decode($this->response->getContent(), true);
        $this->keptClientCommonInfo = $data;
    }

    /**
     * @param $name
     *
     * @Given I search company by name :name around kept region
     */
    public function iSearchCompanyByNameAroundKeptRegion($name)
    {
        $url = '/company/search?regionId=' . $this->keptRegion['id'] . '&name=' . $name;
        $this->sendRequest('GET', $url);
    }

    /**
     * @param $tagNames
     * @param $centerLatitude
     * @param $centerLongitude
     *
     * @Given I select complaints by tags :tagNames and center latitude :centerLatitude and center longitude :centerLongitude
     */
    public function iSelectComplaintsByTagsAroundLocation($tagNames, $centerLatitude, $centerLongitude)
    {
        $tagNames = $this->getTagNames($tagNames);
        $tags = $this->getTagsByNames($tagNames);

        $tagIds = array_map(function ($tag) {
            return $tag->getId();
        }, $tags);
        $tagIds = implode(',', $tagIds);

//        http://localhost:7777/api/client/complaint/geo/search?tags=74&centerLatitude=48.8201034046395&centerLongitude=44.632312059402466

        $url = '/client/complaint/geo/search?tags=' . $tagIds . '&centerLatitude=' . $centerLatitude . '&centerLongitude=' . $centerLongitude;
        $this->sendRequest('GET', $url);
    }

    /**
     * @When a demo scenario sends a request to :path
     */
    public function aDemoScenarioSendsARequestTo(string $path)
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived()
    {
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }
    }

    /**
     * @Given I have an activation key with email :email
     * @param $email
     * @throws \Doctrine\DBAL\DBALException
     */
    public function iHaveAnActivationRegisterKey($email)
    {
        $connection = $this->entityManager->getConnection();

        $statement = $connection->prepare("SELECT client_confirmation_key.key as confirmation_key FROM client_confirmation_key
            JOIN users ON (client_confirmation_key.client_id = users.id)
            WHERE users.email = :email
        ");

        $statement->bindValue("email", $email);
        $statement->execute();

        $key = $statement->fetch(\Doctrine\DBAL\FetchMode::ASSOCIATIVE);

        $this->registerConfirmationKey = $key['confirmation_key'];
    }

    /**
     * @Then I try activate my registration by key
     */
    public function iTryActivateRegistration()
    {
        $this->sendRequest('POST', '/security/confirm-register/' . $this->registerConfirmationKey);
    }


    /**
     * @return \Behat\Mink\Driver\Goutte\Client
     */
    protected function getClient()
    {
        /** @var \Behat\Mink\Driver\Goutte\Client $result */
        $result = $this->getSession('default')->getDriver()->getClient();

        if ($this->authToken !== null)
        {
            $result->setHeader('Authorization', 'Bearer ' . $this->authToken);
        }
        else
        {
            $result->removeHeader('Authorization');
        }

        return $result;
    }

    /**
     * Locates url, based on provided path.
     * Override to provide custom routing mechanism.
     *
     * @param string $path
     *
     * @return string
     */
    public function locatePath($path)
    {
        $startUrl = rtrim($this->getMinkParameter('base_url'), '/') . '/';

        return 0 !== strpos($path, 'http') ? $startUrl . ltrim($path, '/') : $path;
    }

    protected function sendRequest($method, $url, $params = [], $files = [], $server = [], $content = null, $additionHeaders = [
        'Content-Type' => 'application/json'
    ])
    {
        $url = $this->locatePath($url);

        $client = $this->getClient();
        foreach ($additionHeaders as $name => $value)
        {
            $client->setHeader($name, $value);
        }

        $client->request($method, $url, $params, $files, $server, $content);
        $this->response = $client->getInternalResponse();

        return $this->response;
    }

    /**
     * @Then I hold the authorize token from response
     */
    public function iKeepAuthorizationTokenFromRequest()
    {
        $data = json_decode($this->getClient()->getResponse()->getContent(), true);
        $this->authToken = $data['token'];
    }

    /**
     * @When I try to get user profile
     */
    public function iTryToGetUserProfile()
    {
        $this->sendRequest('GET', '/security/profile');
    }

    /**
     * @Given I authorize with email :email and password :password
     * @param $email
     * @param $password
     */
    public function iAuthorize(string $email, string $password)
    {
        $this->sendRequest('POST', '/security/login_check', [], [], [],
            json_encode([
                'username' => $email,
                'password' => $password
            ]));

        Assertions::assertEquals(200, $this->response->getStatus(), 'You can not authorize with this credentials!');

        $this->iKeepAuthorizationTokenFromRequest();
    }

    /**
     * @Given I send http request with method :method on relative url :path with content:
     *
     * @param $method
     * @param $path
     * @param $content
     */
    public function iSendRequestWithContent(string $method, string $path, PyStringNode $content)
    {
        $this->sendRequest($method, $path, [], [], [], $content);
    }

    /**
     * @Given I upload a complaint picture :fileName on server
     * @param $fileName
     */
    public function iUploadComplaintPicture($fileName)
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            __DIR__ . '/../pictures/' . $fileName .'.jpg',
            $fileName . '.jpg',
            'image/jpeg',
            null
        );

        $this->uploadFiles(['imageFile' => $file],'POST', '/client/complaint-picture/create');

        $data = json_decode($this->response->getContent(), true);
        $this->uploadedComplaintPictures[$fileName] = $data['picture']['id'];
    }

    /**
     * @param $fileName
     *
     * @Given I upload a issue picture :fileName on server
     */
    public function iUploadIssuePicture($fileName)
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            __DIR__ . '/../pictures/' . $fileName .'.jpg',
            $fileName . '.jpg',
            'image/jpeg',
            null
        );

        $this->uploadFiles(['imageFile' => $file],'POST', '/client/issue-picture/create');

        $data = json_decode($this->response->getContent(), true);
        $this->uploadedIssuePictures[$fileName] = $data['picture']['id'];
    }

    /**
     * @param $url
     *
     * @Given I create a new video with link :url
     */
    public function iCreateVideo($url)
    {
        $this->sendRequest('POST', '/client/video-material/create', [], [], [], json_encode(['url' => $url]));

        $data = json_decode($this->response->getContent(), true);
        $this->videos[] = $data['video']['id'];
    }

    /**
     * @When I create a new complaint with uploaded pictures and created videos and data:
     * @param \Behat\Gherkin\Node\TableNode $data
     */
    public function iCreateComplaint(\Behat\Gherkin\Node\TableNode $data)
    {
        $formFields = $data->getHash()[0];

        $formData = [
            'message' => $formFields['message'],
            'tags' => explode(',', $formFields['tags']),
            'serviceType' => $formFields['serviceType'],
            'latitude' => $formFields['latitude'],
            'longitude' => $formFields['longitude'],
            'pictures' => array_values($this->uploadedComplaintPictures),
            'videos' => array_values($this->videos)
        ];


        $this->sendRequest('POST', '/client/complaint', [], [], [], json_encode($formData));

        $this->uploadedComplaintPictures = [];
        $this->videos = [];
    }

    /**
     * @param \Behat\Gherkin\Node\TableNode $data
     * @param $id
     *
     * @Given I edit my complaint :id with data:
     */
    public function iUpdateComplaint(\Behat\Gherkin\Node\TableNode $data, $id)
    {
        $formFields = $data->getHash()[0];

        $formData = [
            'message' => $formFields['message'],
            'tags' => explode(',', $formFields['tags']),
            'serviceType' => $formFields['serviceType'],
            'latitude' => $formFields['latitude'],
            'longitude' => $formFields['longitude'],
            'pictures' => array_values($this->uploadedComplaintPictures),
            'videos' => array_values($this->videos)
        ];

        $this->sendRequest('PUT', '/client/complaint/' . $id, [], [], [], json_encode($formData));
    }

    /**
     * @Given I create a new issue with data:
     */
    public function iCreateIssue(\Behat\Gherkin\Node\TableNode $data)
    {
        $formFields = $data->getHash()[0];

        $serviceType = $this->getServiceTypeByTitle($formFields['serviceType']);

        $formData = [
            'message' => $formFields['message'],
            'serviceType' => $serviceType->getId(),
            'latitude' => $formFields['latitude'],
            'longitude' => $formFields['longitude'],
            'pictures' => array_values($this->uploadedIssuePictures),
            'videos' => array_values($this->videos),
            'complaintConfirmations' => !empty($this->keptComplaints) ? array_map(function($item) {
                    return [
                        'complaint' => $item['id']
                    ];
                }, $this->keptComplaints) : [],
            'company' => $this->keptCompany !== null ? $this->keptCompany['id'] : null
        ];

        $this->sendRequest('POST', '/client/issue', [], [], [], json_encode($formData));

        $this->uploadedIssuePictures = [];
        $this->videos = [];
    }

    /**
     * @Given I confirm the first incoming confirmation request
     */
    public function iConfirmLastIncomingComplaint()
    {
        $confirmationId = $this->keptClientCommonInfo['confirmations'][0]['id'];

        $formData = [
            'status' => 'confirmed'
        ];

        $this->sendRequest('PUT', '/client/complaint-confirmation/' .  $confirmationId, [], [], [], json_encode($formData));
    }

    /**
     * @param $userFullName
     * @param $statusCode
     *
     * @Then the complaint of the user :userFullName should have status :statusCode
     * @throws Exception
     */
    public function complaintConfirmationShouldHaveStatus($userFullName, $statusCode)
    {
        $data = json_decode($this->response->getContent(), true);

        $confirmations = $data['issue']['complaintConfirmations'];

        foreach ($confirmations as $confirmation)
        {
            if ($confirmation['complaint']['client']['fullName'] === $userFullName)
            {
                Assertions::assertEquals($statusCode, $confirmation['status']['code']);

                return;
            }
        }

        throw new Exception('The confirmation was not found!');
    }

    /**
     * @param $method
     * @param $url
     * @param $params
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile[] $files
     * @return \Symfony\Component\BrowserKit\Response|null
     */
    protected function uploadFiles(array $files, string $method, string $url, array $params = [])
    {
        $client = $this->getClient();
        $client->removeHeader('Content-Type');

        return $this->sendRequest($method, $url, $params, $files, [], null, []);
    }
}
