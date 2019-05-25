<?php

namespace App\Controller;

use App\Entity\Region;
use App\Repository\RegionRepository;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegionController
 * @package App\Controller
 */
class RegionController extends CommonController
{
    /**
     * @param RegionRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(name="region_get_all", path="/region/list", methods={"GET"})
     */
    public function getAll(RegionRepository $repository)
    {
        $regions = $repository->findBy([], ['title' => 'ASC']);

        return $this->getResponse([
            'regions' => $regions
        ]);
    }

    /**
     * @param Region $region
     * @return Response
     *
     * @Route(name="region_get_details", path="/region/{id}", methods={"GET"})
     * @ParamConverter("region", class="App\Entity\Region")
     */
    public function details(Region $region)
    {
        return $this->getResponse([
            'region' => $region
        ], Response::HTTP_OK, [], ['region_details']);
    }
}