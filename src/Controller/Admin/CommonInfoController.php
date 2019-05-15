<?php

namespace App\Controller\Admin;

use App\Repository\AnalystUserRepository;
use App\Repository\ClientUserRepository;
use App\Repository\CompanyRepresentativeUserRepository;
use App\Repository\ComplaintRepository;
use App\Repository\IssueRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommonInfoController
 * @package App\Controller\Admin
 */
class CommonInfoController extends AdminCommonController
{
    /**
     * @param ComplaintRepository $complaintRepository
     * @param IssueRepository $issueRepository
     * @param CompanyRepresentativeUserRepository $companyRepresentativeUserRepository
     * @param AnalystUserRepository $analystUserRepository
     * @param ClientUserRepository $clientUserRepository
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(name="admin_common_info", path="/common-info", methods={"GET"})
     */
    public function getInfo(
        ComplaintRepository $complaintRepository,
        IssueRepository $issueRepository,
        CompanyRepresentativeUserRepository $companyRepresentativeUserRepository,
        AnalystUserRepository $analystUserRepository,
        ClientUserRepository $clientUserRepository
    )
    {
        // get number of all complaints
        $complaintNumber = $complaintRepository->createQueryBuilder('complaint')
            ->select('COUNT(complaint) as complaintNumber')
            ->getQuery()
            ->getArrayResult();

        // get number of all issues
        $issueNumber = $issueRepository->createQueryBuilder('issue')
            ->select('COUNT(issue) as issueNumber')
            ->getQuery()
            ->getArrayResult();

        $companyAccountNumber = $companyRepresentativeUserRepository->createQueryBuilder('company_representative_user')
            ->select('COUNT(company_representative_user) as companyAccountNumber')
            ->getQuery()
            ->getArrayResult();

        $analystAccountNumber = $analystUserRepository->createQueryBuilder('analyst_user')
            ->select('COUNT(analyst_user) as analystAccountNumber')
            ->getQuery()
            ->getArrayResult();

        $clientAccountNumber = $clientUserRepository->createQueryBuilder('client_user')
            ->select('COUNT(client_user) as clientAccountNumber')
            ->getQuery()
            ->getArrayResult();

        $result = array_merge(
            $complaintNumber[0],
            $issueNumber[0],
            $companyAccountNumber[0],
            $analystAccountNumber[0],
            $clientAccountNumber[0]
        );

        return $this->getResponse($result);
    }
}