<?php

namespace App\Controller\Company;

use App\Repository\IssueRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommonInfoController
 * @package App\Controller\Company
 */
class CommonInfoController extends CompanyCommonController
{
    /**
     * @param IssueRepository $issueRepository
     * @Route(name="company_common_info", path="/common-info", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getInfo(IssueRepository $issueRepository)
    {
        $issueNumber = $issueRepository->createQueryBuilder('issue')
            ->select('COUNT(issue) as issueNumber')
            ->where('issue.company = :company')
            ->setParameter('company', $this->getCompany())
            ->getQuery()
            ->getArrayResult();

        $result = $issueNumber[0];

        return $this->getResponse($result);
    }
}