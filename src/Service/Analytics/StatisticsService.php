<?php

namespace App\Service\Analytics;

use App\Entity\FederalDistrict;
use App\Repository\FederalDistrictRepository;
use App\Repository\IssueRepository;
use App\Repository\RegionRepository;
use App\Repository\ServiceTypeRepository;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

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

    public function getFederalDistrictIssueNumberByYear(FederalDistrict $district, $year)
    {
        $this->getDatePeriodByYear($year, $startTime, $endTime);

        return $this->getFederalDistrictIssueNumberByPeriod($district, $startTime, $endTime);
    }

    public function getFederalDistrictIssueNumberByPeriod(FederalDistrict $district, \DateTime $startDate, \DateTime $endDate)
    {
        $sql = "SELECT 
                    service_type.id AS id, 
                    service_type.title AS title, 
                    service_type.code AS code, 
                    COUNT(district_issues.issue_id) as issue_number 
                FROM service_type
                LEFT JOIN (
                    SELECT 
                        issue.id AS issue_id,
                        issue.service_type_id AS service_type_id
                    FROM issue
                    JOIN region ON(issue.region_id = region.id)
                    WHERE region.federal_district_id = :districtId
                    AND (issue.created_at BETWEEN :startDate AND :endDate)
                    AND (issue.deleted_at IS NULL) 
                ) AS district_issues ON(service_type.id = district_issues.service_type_id)
                GROUP BY service_type.id
                ";


        $statement = $this->entityManager->getConnection()->prepare($sql);
        $statement->execute([
            'districtId' => $district->getId(),
            'startDate' => $startDate->format('Y-m-d H:i:s'),
            'endDate' => $endDate->format('Y-m-d H:i:s')
        ]);

        $data = $statement->fetchAll();

        $result = [];
        foreach ($data as $item)
        {
            $result[] = [
                'serviceType' => [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'code' => $item['code']
                ],
                'issueNumber' => $item['issue_number']
            ];
        }

        /** @var IssueRepository $issueRepository */
        $issueRepository = $this->entityManager->getRepository('App\Entity\Issue');
        $issueWithoutServiceTypeNumber = $issueRepository->createQueryBuilder('issue')
            ->select('COUNT(issue.id) as issueNumber')
            ->join('issue.region', 'region')
            ->where('issue.serviceType IS NULL')
            ->andWhere('issue.createdAt between :startDate and :endDate')
            ->andWhere('region.federalDistrict = :federalDistrict')
            ->setParameter('federalDistrict', $district)
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
        /** @var ServiceTypeRepository $repository */
        $repository = $this->entityManager->getRepository('App\Entity\ServiceType');

        $dynamic = $repository->createQueryBuilder('service_type')
            ->select('service_type.id as id, 
                      service_type.title as title, 
                      service_type.code as code,
                      year(issue.createdAt) as issue_year,
                      month(issue.createdAt) as issue_month, 
                      COUNT(issue.id) as issue_number'
            )
            ->leftJoin('service_type.issues', 'issue', 'WITH', 'issue.createdAt between :startDate and :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('service_type.id, issue_year, issue_month')
            ->orderBy('service_type.id', 'ASC')
            ->addOrderBy('issue_year', 'ASC')
            ->addOrderBy('issue_month', 'ASC')
            ->getQuery()
            ->getResult();

        $result = $this->formatIssueNumberDynamicResult($dynamic);


        /** @var IssueRepository $issueRepository */
        $issueRepository = $this->entityManager->getRepository('App\Entity\Issue');

        $nonServiceTypeIssueDynamic = $issueRepository->createQueryBuilder('issue')
            ->select(
                'year(issue.createdAt) as issue_year,
                 month(issue.createdAt) as issue_month, 
                 COUNT(issue.id) as issue_number'
            )
            ->where('issue.createdAt between :startDate and :endDate')
            ->andWhere('issue.serviceType IS NULL')
            ->groupBy('issue_year, issue_month')
            ->orderBy('issue_year', 'ASC')
            ->addOrderBy('issue_month', 'ASC')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();

        if (count($nonServiceTypeIssueDynamic) > 0)
        {
            $nonServiceTypeDynamic = [];

            $currentYearHash = [];
            $currentYear = null;

            foreach ($nonServiceTypeIssueDynamic as $item)
            {
                if ($currentYear !== $item['issue_year'])
                {
                    if (!empty($currentYearHash))
                    {
                        $nonServiceTypeDynamic[$currentYear] = $currentYearHash;
                    }

                    $currentYear = $item['issue_year'];

                    $currentYearHash = [];
                }

                $currentYearHash[$item['issue_month']] = $item['issue_number'];
            }

            if (!empty($currentYearHash))
            {
                $nonServiceTypeDynamic[$currentYear] = $currentYearHash;
            }

            $result[] = [
                'years' => $nonServiceTypeDynamic
            ];
        }

        return $result;
    }

    private function formatIssueNumberDynamicResult(array $rawData)
    {
        $result = [];

        $currentServiceTypeId = null;
        $currentRow = [];
        foreach ($rawData as $item)
        {
            if ($currentServiceTypeId !== $item['id'])
            {
                $currentServiceTypeId = $item['id'];
                if (!empty($currentRow))
                {
                    $result[] = $currentRow;
                }

                $currentRow = [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'code' => $item['code'],
                    'years' => []
                ];
            }

            if (!is_null($item['issue_year']))
            {
                $currentRow['years'][$item['issue_year']] = [];
            }

            if (!is_null($item['issue_month']))
            {
                $currentRow['years'][$item['issue_year']][$item['issue_month']] = $item['issue_number'];
            }
        }

        if (!empty($currentRow))
        {
            $result[] = $currentRow;
        }

        return $result;
    }

    public function getFederalDistrictIssueNumberDynamicYear(FederalDistrict $district, $year)
    {
        $this->getDatePeriodByYear($year, $startTime, $endTime);

        return $this->getFederalDistrictIssueNumberDynamicByPeriod($district, $startTime, $endTime);
    }

    public function getFederalDistrictIssueNumberDynamicByPeriod(FederalDistrict $district, \DateTime $startDate, \DateTime $endDate)
    {

        $sql = "SELECT 
                     service_type.id as id,
                     service_type.title as title,
                     service_type.code as code,
                     district_issues.issue_year as issue_year,
                     district_issues.issue_month as issue_month,
                     COUNT(district_issues.issue_id) as issue_number
                FROM service_type
                LEFT JOIN (
                    SELECT 
                        issue.id as issue_id,
                        EXTRACT(YEAR FROM issue.created_at) as issue_year,
                        EXTRACT(MONTH from issue.created_at) as issue_month,
                        issue.service_type_id as service_type_id
                    FROM issue
                    JOIN region ON (region.id = issue.region_id)
                    WHERE (region.federal_district_id = :federalDistrcitId)
                    AND (issue.created_at BETWEEN :startDate AND :endDate )     
                    AND (issue.deleted_at IS NULL)            
                ) AS district_issues ON (district_issues.service_type_id = service_type.id)
                GROUP BY service_type.id, district_issues.issue_year, district_issues.issue_month
                ORDER BY id, issue_year, issue_month
        ";

        $statement = $this->entityManager->getConnection()->prepare($sql);
        $statement->execute([
            'federalDistrcitId' => $district->getId(),
            'startDate' => $startDate->format('Y-m-d H:i:s'),
            'endDate' => $endDate->format('Y-m-d H:i:s')
        ]);

        $dynamic = $statement->fetchAll();
        $result = $this->formatIssueNumberDynamicResult($dynamic);


        $nonServiceTypeIssueDynamic = $this->getCommonNonServiceTypeIssueNumberDynamicQueryBuilder($startDate, $endDate)
            ->join('issue.region', 'region', 'WITH', 'region.federalDistrict = :federalDistrict')
            ->setParameter('federalDistrict', $district)
            ->getQuery()
            ->getResult();


        if (count($nonServiceTypeIssueDynamic) > 0)
        {
            $monthsDynamic = [];
            foreach ($nonServiceTypeIssueDynamic as $item)
            {
                $monthsDynamic[$item['issueMonth']] = $item['issueNumber'];
            }

            $result[] = [
                'months' => $monthsDynamic
            ];
        }

        return $result;

    }

    private function getCommonNonServiceTypeIssueNumberDynamicQueryBuilder(\DateTime $startDate, \DateTime $endDate): QueryBuilder
    {
        /** @var IssueRepository $issueRepository */
        $issueRepository = $this->entityManager->getRepository('App\Entity\Issue');

        $result = $issueRepository->createQueryBuilder('issue')
            ->select(
                'month(issue.createdAt) as issueMonth,
                 COUNT(issue.id) as issueNumber'
            )
            ->where('issue.createdAt between :startDate and :endDate')
            ->andWhere('issue.serviceType IS NULL')
            ->groupBy('issueMonth')
            ->orderBy('issueMonth', 'ASC')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ;

        return $result;
    }



    public function getIssueNumbersOfFederalDistrictsByYear($year)
    {
        $this->getDatePeriodByYear($year, $startTime, $endTime);

        return $this->getIssueNumbersOfFederalDistrictsByPeriod($startTime, $endTime);
    }

    public function getIssueNumbersOfFederalDistrictsByPeriod(\DateTime $startDate, \DateTime $endDate)
    {

        $sql = "
            SELECT 
                federal_district.id AS f_id,
                federal_district.title AS f_title,
                federal_district.code AS f_code,
                period_issues.service_type_id as s_id,
                period_issues.service_type_title AS s_title,
                period_issues.service_type_code AS s_code,
                COUNT(period_issues.issue_id) AS issue_number                
            FROM federal_district
            JOIN region ON (federal_district.id = region.federal_district_id)
            LEFT JOIN (
                SELECT
                     issue.id AS issue_id,
                     issue.region_id AS region_id, 
                     service_type.id AS service_type_id,
                     service_type.title AS service_type_title,
                     service_type.code as service_type_code
                FROM issue
                LEFT JOIN service_type ON(service_type.id = issue.service_type_id)
                WHERE
                    (issue.deleted_at IS NULL)
                AND
                    (issue.created_at BETWEEN :startDate and :endDate)
            ) AS period_issues ON (region.id = period_issues.region_id)
            GROUP BY
                federal_district.id,
                period_issues.service_type_id,
                period_issues.service_type_title,
                period_issues.service_type_code
            ORDER BY
                federal_district.id      
        ";

        $statement = $this->entityManager->getConnection()->prepare($sql);
        $statement->execute([
            'startDate' => $startDate->format('Y-m-d H:i:s'),
            'endDate' => $endDate->format('Y-m-d H:i:s')
        ]);

        $data = $statement->fetchAll();

        $result = [];

        $currentFederalDistrictId = null;
        $currentRow = [];

        foreach ($data as $item)
        {
            if ($item['f_id'] !== $currentFederalDistrictId)
            {
                $currentFederalDistrictId = $item['f_id'];

                if (!empty($currentRow))
                {
                    $result[] = $currentRow;
                }

                $currentRow = [
                    'id' => $item['f_id'],
                    'title' => $item['f_title'],
                    'code' => $item['f_code']
                ];

                $currentRow['serviceTypes'] = [];
            }


            $currentRow['serviceTypes'][] = [
                'id' => $item['s_id'],
                'title' => $item['s_title'],
                'code' => $item['s_code'],
                'issueNumber' => $item['issue_number']
            ];
        }

        if (!empty($currentRow))
        {
            $result[] = $currentRow;
        }

        return $result;
    }

    public function getIssueNumbersOfRegionsByYear(FederalDistrict $district, $year)
    {
        $this->getDatePeriodByYear($year, $startTime, $endTime);

        return $this->getIssueNumbersOfRegionsByPeriod($district, $startTime, $endTime);
    }

    public function getIssueNumbersOfRegionsByPeriod(FederalDistrict $district, \DateTime $startDate, \DateTime $endDate)
    {
        $sql = "
            SELECT
                region.id AS r_id,
                region.title AS r_title,
                region.code AS r_code,
                district_issues.service_type_id AS s_id,
                district_issues.service_type_title AS s_title,
                district_issues.service_type_code AS s_code,
                COUNT(district_issues.issue_id) AS issue_number
            FROM region
            LEFT JOIN (
                SELECT
                    issue.id AS issue_id,
                    issue.region_id AS region_id,
                    service_type.id AS service_type_id,
                    service_type.title AS service_type_title,
                    service_type.code as service_type_code                  
                FROM issue
                LEFT JOIN service_type ON(issue.service_type_id = service_type.id)
                WHERE (issue.created_at BETWEEN :startDate and :endDate)
                AND (issue.deleted_at IS NULL) 
            ) AS district_issues ON (region.id = district_issues.region_id)
            WHERE region.federal_district_id = :districtId
            GROUP BY 
                region.id, 
                district_issues.service_type_id, 
                district_issues.service_type_title,
                district_issues.service_type_code
            ORDER BY region.id ASC
        ";
        $statement = $this->entityManager->getConnection()->prepare($sql);
        $statement->execute([
            'districtId' => $district->getId(),
            'startDate' => $startDate->format('Y-m-d H:i:s'),
            'endDate' => $endDate->format('Y-m-d H:i:s')
        ]);

        $data = $statement->fetchAll();

        $result = [];

        $currentRegionId = null;
        $currentRow = [];
        foreach ($data as $item)
        {
            if ($item['r_id'] !== $currentRegionId)
            {
                $currentRegionId = $item['r_id'];

                if (!empty($currentRow))
                {
                    $result[] = $currentRow;
                }

                $currentRow = [
                    'id' => $item['r_id'],
                    'title' => $item['r_title'],
                    'code' => $item['r_code']
                ];

                $currentRow['serviceTypes'] = [];
            }


            $currentRow['serviceTypes'][] = [
                'id' => $item['s_id'],
                'title' => $item['s_title'],
                'code' => $item['s_code'],
                'issueNumber' => $item['issue_number']
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