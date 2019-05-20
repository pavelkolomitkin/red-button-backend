<?php

namespace App\Service\Analytics;

use App\Repository\FederalDistrictRepository;
use App\Repository\IssueRepository;
use App\Repository\ServiceTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class StatisticsService
 * @package App\Service\Analytics
 */
class StatisticsService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     *
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCountryServiceTypeIssueNumberByYear($year)
    {
        $this->getDatePeriodByYear($year, $startTime, $endTime);

        return $this->getCountryServiceTypeIssueNumbersByPeriod($startTime, $endTime);
    }

    public function getCountryServiceTypeIssueNumbersByPeriod(\DateTime $startDate, \DateTime $endDate)
    {
        /** @var ServiceTypeRepository $serviceTypeRepository */
        $serviceTypeRepository = $this->entityManager->getRepository('App\Entity\ServiceType');

        $result = $serviceTypeRepository->createQueryBuilder('service_type')
            ->select(['service_type as serviceType', 'COUNT(issue.id) as issueNumber'])
            ->leftJoin('service_type.issues', 'issue', 'WITH', 'issue.createdAt between :startDate and :endDate')
            ->groupBy('service_type.id')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult()
        ;

        /** @var IssueRepository $issueRepository */
        $issueRepository = $this->entityManager->getRepository('App\Entity\Issue');
        $issueWithoutServiceTypeNumber = $issueRepository->createQueryBuilder('issue')
            ->select('COUNT(issue.id) as issueNumber')
            ->where('issue.serviceType IS NULL')
            ->andWhere('issue.createdAt between :startDate and :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult()
            ;

        $result[] = [
            'serviceType' => null,
            'issueNumber' => $issueWithoutServiceTypeNumber[0]['issueNumber']
        ];

        return $result;
    }

    public function getIssueNumberDynamicByYear($year)
    {
        $this->getDatePeriodByYear($year, $startTime, $endTime);

        return $this->getIssueNumberDynamicByPeriod($startTime, $endTime);
    }

    public function getIssueNumberDynamicByPeriod(\DateTime $startDate, \DateTime $endDate)
    {
        /** @var IssueRepository $repository */
        $repository = $this->entityManager->getRepository('App\Entity\Issue');

        $dynamic = $repository->createQueryBuilder('issue')
            ->select('st.id as sId, st.title as sTitle, month(issue.createdAt) as issueMonth, COUNT(issue.id) as issueNumber')
            ->leftJoin('issue.serviceType', 'st', 'WITH', 'issue.createdAt between :startDate and :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('st, issueMonth')
            ->orderBy('st.id', 'ASC')
            ->addOrderBy('issueMonth', 'ASC')
            ->getQuery()
            ->getResult()
            ;

        $result = [];

        $currentServiceTypeId = null;
        $currentRow = [];
        foreach ($dynamic as $item)
        {
            if ($currentServiceTypeId !== $item['sId'])
            {
                $currentServiceTypeId = $item['sId'];
                if (!empty($currentRow))
                {
                    $result[] = $currentRow;
                }

                $currentRow = [
                    'id' => $item['sId'],
                    'title' => $item['sTitle'],
                    'months' => []
                ];
            }

            $currentRow['months'][$item['issueMonth']] = $item['issueNumber'];
        }

        if (!empty($currentRow))
        {
            $result[] = $currentRow;
        }

        return $result;
    }

    public function getIssueNumbersOfFederalDistrictsByYear($year)
    {
        $this->getDatePeriodByYear($year, $startTime, $endTime);

        return $this->getIssueNumbersOfFederalDistrictsByPeriod($startTime, $endTime);
    }

    public function getIssueNumbersOfFederalDistrictsByPeriod(\DateTime $startDate, \DateTime $endDate)
    {

        /** @var FederalDistrictRepository $repository */
        $repository = $this->entityManager->getRepository('App\Entity\FederalDistrict');

        $data = $repository->createQueryBuilder('federalDistrict')
            ->select('federalDistrict.id as fId, federalDistrict.title as fTitle, federalDistrict.code as fCode, serviceType.id as sId, serviceType.title as sTitle, COUNT(issue.id) as issueNumber')
            ->join('federalDistrict.regions', 'region')
            ->leftJoin('region.issues', 'issue', 'WITH', 'issue.createdAt between :startDate and :endDate')
            ->leftJoin('issue.serviceType', 'serviceType')
            ->orderBy('federalDistrict.id', 'ASC')
            ->groupBy('federalDistrict.id, serviceType.id')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult()
        ;

        $result = [];

        $currentFederalDistrictId = null;
        $currentRow = [];

        foreach ($data as $item)
        {
            if ($item['fId'] !== $currentFederalDistrictId)
            {
                $currentFederalDistrictId = $item['fId'];

                if (!empty($currentRow))
                {
                    $result[] = $currentRow;
                }

                $currentRow = [
                    'id' => $item['fId'],
                    'title' => $item['fTitle'],
                    'code' => $item['fCode']
                ];

                $currentRow['serviceTypes'] = [];
            }


            $currentRow['serviceTypes'][] = [
                'id' => $item['sId'],
                'title' => $item['sTitle'],
                'issueNumber' => $item['issueNumber']
            ];
        }

        if (!empty($currentRow))
        {
            $result[] = $currentRow;
        }

        return $result;
    }

    private function getDatePeriodByYear($year, \DateTime &$startTime = null, \DateTime &$endTime = null)
    {
        $startTime = new \DateTime();
        $startTime->setTime(0, 0, 0);
        $startTime->setDate($year, 1, 1);

        $endTime = new \DateTime();
        $endTime->setTime(23, 59, 59);
        $endTime->setDate($year, 12, 31);
    }
}