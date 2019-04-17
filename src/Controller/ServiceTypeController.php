<?php

namespace App\Controller;

use App\Repository\ServiceTypeRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServiceTypeController
 * @package App\Controller
 */
class ServiceTypeController extends CommonController
{
    /**
     * @param ServiceTypeRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(name="service_type_list", path="/service-type/list", methods={"GET"})
     */
    public function list(ServiceTypeRepository $repository)
    {
        $serviceTypes = $repository->findAll();

        return $this->getResponse([
            'serviceTypes' => $serviceTypes
        ]);
    }
}
