<?php

namespace App\Controller\Admin;

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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(name="admin_common_info", path="/common-info", methods={"GET"})
     */
    public function getInfo(ComplaintRepository $complaintRepository, IssueRepository $issueRepository)
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
            ->getResult();

        $result = array_merge($complaintNumber[0], $issueNumber[0]);

        return $this->getResponse($result);
    }
}