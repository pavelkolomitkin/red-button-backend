<?php

namespace App\Controller;

use App\Repository\RegionRepository;
use App\Service\EntityManager\Exception\ManageEntityException;
use App\Service\Geo\Exception\GeoLocationException;
use App\Service\Geo\GeoLocationService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeoLocationController extends CommonController
{
    /**
     * @param Request $request
     * @param GeoLocationService $locationService
     * @param RegionRepository $repository
     * @return object|void
     * @throws ManageEntityException
     * @Route(name="geo_get", path="/geo/get", methods={"GET"})
     */
    public function getRegion(Request $request, GeoLocationService $locationService, RegionRepository $repository)
    {
        try
        {
            $latitude = $request->query->get('latitude', 0);
            $longitude = $request->query->get('longitude', 0);

            $osmAddress = $locationService->getOSMAddress($latitude, $longitude);

            $region = $repository->findOneBy(['title' => $osmAddress->getState()]);
            if (!$region)
            {
                throw new ManageEntityException(['location' => 'Cannot to identify the region!'], ManageEntityException::READ_ENTITY_ERROR_TYPE);
            }

            return $this->getResponse([
                'region' => $region,
                'addition' => $osmAddress
            ]);
        }
        catch (GeoLocationException $exception)
        {
            throw new ManageEntityException(['location' => $exception->getMessage()], ManageEntityException::READ_ENTITY_ERROR_TYPE);
        }
    }
}
