<?php

namespace App\Service\EntityManager\Client;

use App\Entity\Complaint;
use App\Form\Client\ComplaintType;
use App\Service\EntityManager\CommonEntityManager;
use App\Service\EntityManager\Exception\ManageEntityException;
use App\Service\Geo\Exception\GeoLocationException;
use App\Service\Geo\IGeoLocationService;
use App\Service\UserAwareServiceTrait;
use Symfony\Component\Form\FormInterface;

/**
 * Class ComplaintManager
 * @package App\Service\EntityManager\Client
 */
class ComplaintManager extends CommonEntityManager
{
    use UserAwareServiceTrait;

    /**
     * @var IGeoLocationService
     */
    private $locationService;

    protected function getCreationForm(): FormInterface
    {
        $entity = new Complaint();
        $entity->setClient($this->getUser());

        return $this->formFactory->create(ComplaintType::class, $entity);
    }

    protected function getUpdatingForm(): FormInterface
    {
        return $this->formFactory->create(ComplaintType::class);
    }

    protected function setGeoLocationService(IGeoLocationService $locationService)
    {
        $this->locationService = $locationService;
        return $this;
    }

    /**
     * @param Complaint $entity
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
            throw new ManageEntityException(['latitude' => $errorMessage, 'longitude' => $errorMessage]);
        }


        // set new address
        // set new region
        $entity
            ->setAddress($address)
            ->setRegion($region);


        // process tags
    }
}
