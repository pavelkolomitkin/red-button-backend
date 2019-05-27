<?php

namespace App\Controller\Analytics;

use App\Entity\Company;
use App\Entity\Region;
use App\Service\Analytics\StatisticsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class CompanyController
 * @package App\Controller\Analytics
 */
class CompanyController extends AnalyticsCommonController
{
    /**
     * @param Company $company
     *
     * @Route(name="company_get_details", path="/company/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @ParamConverter("company", class="App\Entity\Company")
     */
    public function details(Company $company)
    {
        return $this->getResponse([
            'company' => $company
        ], Response::HTTP_OK, [], [
            'analyst_details'
        ]);
    }
}