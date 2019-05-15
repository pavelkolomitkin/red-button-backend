<?php

namespace App\Controller;

use App\Repository\RegionRepository;

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
}