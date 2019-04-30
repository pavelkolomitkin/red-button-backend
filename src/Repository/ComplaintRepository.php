<?php

namespace App\Repository;

use App\Entity\Complaint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Complaint|null find($id, $lockMode = null, $lockVersion = null)
 * @method Complaint|null findOneBy(array $criteria, array $orderBy = null)
 * @method Complaint[]    findAll()
 * @method Complaint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplaintRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Complaint::class);
    }


    public function getSearchQuery(array $criteria = []): Query
    {
        $builder = $this->createQueryBuilder('complaint');

        $this->handleOwnerParameter($builder, $criteria);
        $this->handleGeoBoundariesParameters($builder, $criteria);
        $this->handleServiceTypeParameter($builder, $criteria);
        $this->handleTimePeriodParameters($builder, $criteria);
        $this->handleTagsParameter($builder, $criteria);

        $builder->addOrderBy('complaint.createdAt', 'DESC');

        return $builder->getQuery();
    }

    private function handleGeoBoundariesParameters(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (isset($criteria['centerLatitude']) && isset($criteria['centerLongitude']))
        {
            // TODO: filtering by the neighbourhood of the center point
        }
        elseif (isset($criteria['topLeftLatitude']) && isset($criteria['topLeftLongitude'])
            && isset($criteria['bottomRightLatitude']) && isset($criteria['bottomRightLongitude']))
        {
            // TODO: filtering by the pointed frame
        }

        return $builder;
    }

    private function handleTagsParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (!empty($criteria['tags']))
        {
            $builder->join('complaint.tags', 'tag')
                ->andWhere('tag.title IN (:tags)')
                ->setParameter('tags', $criteria['tags']);
        }

        return $builder;
    }

    private function handleTimePeriodParameters(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (isset($criteria['timeStart']))
        {
            $timeEnd = isset($criteria['timeEnd']) ? $criteria['timeEnd'] : time();
            $timeStart = $criteria['timeStart'];

            $timeStart = $timeStart instanceof \DateTime ? $timeStart : new \DateTime($timeStart);
            $timeEnd = $timeStart instanceof \DateTime ? $timeEnd : new \DateTime($timeEnd);

            $builder->andWhere('complaint.createdAt BETWEEN :timeStart AND :timeEnd')
                ->setParameter('timeStart', $timeStart)
                ->setParameter('timeEnd', $timeEnd);
        }

        return $builder;
    }

    private function handleServiceTypeParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (!empty($criteria['serviceTypeId']))
        {
            $builder
                ->andWhere('complaint.serviceType = :serviceType')
                ->setParameter('serviceType', $criteria['serviceTypeId']);
        }

        return $builder;
    }

    private function handleOwnerParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (isset($criteria['owner']))
        {
            $builder
                ->andWhere('complaint.client = :client')
                ->setParameter('client', $criteria['owner']);
        }

        return $builder;
    }

}
