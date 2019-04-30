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
    private $searchingRadius;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Complaint::class);
    }

    public function setSearchingRadius($radius)
    {
        $this->searchingRadius = $radius;
    }

    public function hasGeoCriteria(array $criteria)
    {
        return $this->hasGeoBoundariesCriteria($criteria) || $this->hasGeoNearCriteria($criteria);
    }

    public function hasGeoBoundariesCriteria(array $criteria)
    {
        return isset($criteria['topLeftLatitude']) && isset($criteria['topLeftLongitude'])
            && isset($criteria['bottomRightLatitude']) && isset($criteria['bottomRightLongitude']);
    }

    public function hasGeoNearCriteria(array $criteria)
    {
        return isset($criteria['centerLatitude']) && isset($criteria['centerLongitude']);
    }

    public function getSearchQuery(array $criteria = []): Query
    {
        $builder = $this->createQueryBuilder('complaint');

        $this->handleClientParameter($builder, $criteria);
        $this->handleNearPointParameter($builder, $criteria);
        $this->handleGeoBoundariesParameters($builder, $criteria);
        $this->handleServiceTypeParameter($builder, $criteria);
        $this->handleTimePeriodParameters($builder, $criteria);
        $this->handleTagsParameter($builder, $criteria);

        $builder->addOrderBy('complaint.createdAt', 'DESC');

        return $builder->getQuery();
    }

    private function handleNearPointParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if ($this->hasGeoNearCriteria($criteria))
        {
            // TODO: filtering by the neighbourhood of the center point
            // TODO: move the radius to the config
            $builder->andWhere('
                ST_Distance(
                    Geography(ST_Point(:selectedLongitude, :selectedLatitude)),
                    Geography(ST_Point(complaint.address.longitude, complaint.address.latitude))
                ) / 1000 <= :radius
                ')
                ->setParameter('selectedLatitude', $criteria['centerLatitude'])
                ->setParameter('selectedLongitude', $criteria['centerLongitude'])
                ->setParameter('radius', $this->searchingRadius);
        }

        return $builder;
    }

    private function handleGeoBoundariesParameters(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if ($this->hasGeoBoundariesCriteria($criteria))
        {
            $builder
                ->andWhere('complaint.address.latitude <= :topLeftLatitude')
                ->andWhere('complaint.address.longitude >= :topLeftLongitude')
                ->andWhere('complaint.address.latitude >= :bottomRightLatitude')
                ->andWhere('complaint.address.longitude <= :bottomRightLongitude')

                ->setParameter('topLeftLatitude', $criteria['topLeftLatitude'])
                ->setParameter('topLeftLongitude', $criteria['topLeftLongitude'])
                ->setParameter('bottomRightLatitude', $criteria['bottomRightLatitude'])
                ->setParameter('bottomRightLongitude', $criteria['bottomRightLongitude'])
            ;
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

    private function handleClientParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (isset($criteria['client']))
        {
            $builder
                ->andWhere('complaint.client = :client')
                ->setParameter('client', $criteria['client']);
        }

        return $builder;
    }

}
