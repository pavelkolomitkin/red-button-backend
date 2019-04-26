<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\MinkContext;
use PHPUnit\Framework\Assert as Assertions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

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
     * @var array array of video ids
     */
    private $videos = [];

    public function __construct(
        KernelInterface $kernel,
        \Doctrine\ORM\EntityManagerInterface $entityManager
    )
    {
        $this->kernel = $kernel;
        $this->entityManager = $entityManager;

        $this->kernel->getContainer()->set(App\Service\Geo\IGeoLocationService::class, new \App\Service\Geo\TestEnvGeoLocationService());
        $this->kernel->getContainer()->set(App\Service\Video\IExternalVideoProvider::class, new \App\Service\Video\TestEnvExternalVideoProvider());
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
     * @Then I hold last note
     */
    public function iHoldLastNote()
    {
        $data = json_decode($this->response->getContent(), true);
        $this->lastNote = $data['note'];
    }

    /**
     * @Given I upload a complaint picture :fileName on server
     * @param $fileName
     */
    public function iUploadComplaintPicture($fileName)
    {
        $client = $this->getClient();
        $client->removeHeader('Content-Type');

        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            __DIR__ . '/../pictures/' . $fileName .'.jpg',
            'file_1.jpg',
            'image/jpeg',
            null
        );

        $this->uploadFiles(['imageFile' => $file],'POST', '/client/complaint-picture/create');

        $data = json_decode($this->response->getContent(), true);
        $this->uploadedComplaintPictures[$fileName] = $data['picture']['id'];
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
     * @param $method
     * @param $url
     * @param $params
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile[] $files
     * @return \Symfony\Component\BrowserKit\Response|null
     */
    protected function uploadFiles(array $files, string $method, string $url, array $params = [])
    {
        return $this->sendRequest($method, $url, $params, $files, [], null, []);
    }
}
