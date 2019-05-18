<?php

namespace App\Repository;

use App\Entity\Issue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Issue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Issue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Issue[]    findAll()
 * @method Issue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IssueRepository extends ServiceEntityRepository implements ISearchRepository
{
    use GeoCriteriaAwareTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Issue::class);
    }

    public function getSearchQuery(array $criteria): Query
    {
        $builder = $this->createQueryBuilder('issue');

        $this->handleClientParameter($builder, $criteria);
        $this->handleCompanyParameter($builder, $criteria);
        $this->handleDatePeriodParameter($builder, $criteria);
        $this->handleRegionParameter($builder, $criteria);
        $this->handleServiceTypeParameter($builder, $criteria);
        $this->handleGeoBoundariesParameters($builder, $criteria);

        $this->handleOrdering($builder, $criteria);

        return $builder->getQuery();
    }

    private function handleGeoBoundariesParameters(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if ($this->hasGeoBoundariesCriteria($criteria))
        {
            $builder
                ->andWhere('issue.address.latitude <= :topLeftLatitude')
                ->andWhere('issue.address.longitude >= :topLeftLongitude')
                ->andWhere('issue.address.latitude >= :bottomRightLatitude')
                ->andWhere('issue.address.longitude <= :bottomRightLongitude')

                ->setParameter('topLeftLatitude', $criteria['topLeftLatitude'])
                ->setParameter('topLeftLongitude', $criteria['topLeftLongitude'])
                ->setParameter('bottomRightLatitude', $criteria['bottomRightLatitude'])
                ->setParameter('bottomRightLongitude', $criteria['bottomRightLongitude'])
            ;
        }

        return $builder;
    }

    private function handleOrdering(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (!empty($criteria['popular']))
        {
            $builder->orderBy('issue.likeNumber', 'DESC');
        }

        $builder->addOrderBy('issue.createdAt', 'DESC');

        return $builder;
    }

    private function handleClientParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (isset($criteria['client']))
        {
            $builder
                ->andWhere('issue.client = :client')
                ->setParameter('client', $criteria['client']);
        }

        return $builder;
    }

    private function handleCompanyParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (isset($criteria['company']))
        {
            $builder
                ->andWhere('issue.company = :company')
                ->setParameter('company', $criteria['company']);
        }

        return $builder;
    }

    private function handleDatePeriodParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (isset($criteria['startDate']) && isset($criteria['endDate']))
        {
            $startDate = $criteria['startDate'] instanceof \DateTime
                ? $criteria['startDate'] : new \DateTime($criteria['startDate']);

            $endDate = $criteria['endDate'] instanceof \DateTime
                ? $criteria['endDate'] : new \DateTime($criteria['endDate']);

            $builder
                ->andWhere('issue.createdAt between :startDate and :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate);

        }

        return $builder;
    }

    private function handleRegionParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (!empty($criteria['region']))
        {
            $builder
                ->andWhere('issue.region = :region')
                ->setParameter('region', $criteria['region']);
        }

        return $builder;
    }

    private function handleServiceTypeParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (!empty($criteria['serviceType']))
        {
            $builder
                ->andWhere('issue.serviceType = :serviceType')
                ->setParameter('serviceType', $criteria['serviceType']);
        }

        return $builder;
    }
}
