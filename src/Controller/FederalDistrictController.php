<?php

namespace App\Controller;

use App\Repository\FederalDistrictRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FederalDistrictController
 * @package App\Controller
 */
class FederalDistrictController extends CommonController
{
    /**
     * @param FederalDistrictRepository $repository
     *
     * @Route(name="federal_district_get_all", path="/federal-district/list", methods={"GET"})
     * @return Response
     */
    public function getAll(FederalDistrictRepository $repository)
    {
        $items = $repository->findBy([], ['title' => 'ASC']);

        return $this->getResponse([
            'list' => $items
        ]);
    }
}