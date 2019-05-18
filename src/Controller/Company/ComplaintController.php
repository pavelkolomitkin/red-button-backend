<?php

namespace App\Controller\Company;

use App\Entity\Complaint;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComplaintController extends CompanyCommonController
{
    /**
     * @param Complaint $complaint
     * @Route(name="company_complaint_get", path="/complaint/{id}", methods={"GET"})
     * @ParamConverter("complaint", class="App\Entity\Complaint")
     * @return Response
     */
    public function details(Complaint $complaint)
    {
        return $this->getResponse([
            'complaint' => $complaint
        ]);
    }
}