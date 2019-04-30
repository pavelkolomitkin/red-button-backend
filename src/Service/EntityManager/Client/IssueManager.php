<?php


namespace App\Service\EntityManager\Client;


use App\Entity\Issue;
use App\Form\Client\IssueType;
use App\Service\EntityManager\CommonEntityManager;
use App\Service\EntityManager\Exception\ManageEntityException;
use App\Service\Geo\Exception\GeoLocationException;
use App\Service\Geo\IGeoLocationService;
use App\Service\UserAwareServiceTrait;
use Symfony\Component\Form\FormInterface;

/**
 * Class IssueManager
 * @package App\Service\EntityManager\Client
 */
class IssueManager extends CommonEntityManager
{
    use UserAwareServiceTrait;

    /**
     * @var IGeoLocationService
     */
    private $locationService;

    protected function getCreationForm(): FormInterface
    {
        $issue = new Issue();
        $issue->setClient($this->getUser());

        return $this->formFactory->create(IssueType::class, $issue);
    }

    protected function getUpdatingForm(): FormInterface
    {
        return $this->formFactory->create(IssueType::class);
    }

    /**
     * @param IGeoLocationService $locationService
     * @return IssueManager
     *
     * @required
     */
    public function setGeoLocationService(IGeoLocationService $locationService): self
    {
        $this->locationService = $locationService;
        return $this;
    }

    /**
     * @param Issue $entity
     * @param $data
     * @throws ManageEntityException
     */
    protected function postValidate($entity, $data)
    {
        try
        {
            // get address external data
            $address = $this->locationService->getOSMAddress($data['latitude'], $data['longitude']);
        }
        catch (GeoLocationException $exception)
        {
            // if an error
            // throw exception
            throw new ManageEntityException(['latitude' => $exception->getMessage(), 'longitude' => $exception->getMessage()]);
        }

        // get region by address state
        $region = $this
            ->entityManager
            ->getRepository('App\Entity\Region')
            ->findOneBy(['title' => $address->getState()]);
        // if an error
        if (!$region)
        {
            $errorMessage = 'Cannot identify the region!';
            // throw exception
            throw new ManageEntityException(['location' => $errorMessage]);
        }

        // set new address
        // set new region
        $entity
            ->setAddress($address)
            ->setRegion($region);
    }
}