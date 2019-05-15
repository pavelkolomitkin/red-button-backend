<?php

namespace App\Repository;

use App\Entity\Complaint;
use App\Entity\ComplaintTag;
use App\Repository\ComplaintTagRepository;
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

    /**
     * @var ComplaintTagRepository
     */
    private $complaintTagRepository;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Complaint::class);
    }

    public function setSearchingRadius($radius)
    {
        $this->searchingRadius = $radius;
    }

    /**
     * @param \App\Repository\ComplaintTagRepository $complaintTagRepository
     *
     * @required
     */
    public function setComplaintTagRepository(ComplaintTagRepository $complaintTagRepository)
    {
        $this->complaintTagRepository = $complaintTagRepository;
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

    public function getTagSearchQuery(array $criteria): Query
    {

        /** @var ComplaintTagRepository $tagRepository */
        $tagRepository = $this->getEntityManager()->getRepository(ComplaintTag::class);

        $builder = $tagRepository->createQueryBuilder('t')
            ->select('t as tag, COUNT(complaint) as complaintNumber')
            ->join('t.complaints', 'complaint')
        ;

        $this->handleNearPointParameter($builder, $criteria);
        $this->handleGeoBoundariesParameters($builder, $criteria);
        $this->handleServiceTypeParameter($builder, $criteria);
        $this->handleDatePeriodParameter($builder, $criteria);


        $builder
            ->groupBy('t')
            ->orderBy('complaintNumber', 'DESC')
            ->addOrderBy('t.title', 'ASC')
        ;


        return $builder->getQuery();
    }

    public function getSearchQuery(array $criteria = []): Query
    {
        $builder = $this->createQueryBuilder('complaint');

        $this->handleClientParameter($builder, $criteria);
        $this->handleNearPointParameter($builder, $criteria);
        $this->handleGeoBoundariesParameters($builder, $criteria);
        $this->handleServiceTypeParameter($builder, $criteria);
        $this->handleTagsParameter($builder, $criteria);
        $this->handleRegionParameter($builder, $criteria);
        $this->handleDatePeriodParameter($builder, $criteria);

        $builder->addOrderBy('complaint.createdAt', 'DESC');

        return $builder->getQuery();
    }

    private function handleNearPointParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if ($this->hasGeoNearCriteria($criteria))
        {
            $builder->andWhere('
                ST_Distance(
                    Geography(ST_Point(:selectedLongitude, :selectedLatitude)),
                    Geography(ST_Point(complaint.address.longitude, complaint.address.latitude))
                ) <= :radius
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
        $tags = !empty($criteria['tags']) ? explode(',', $criteria['tags']) : [];

        if (!empty($tags))
        {
            $builder->join('complaint.tags', 'tag')
                ->andWhere('tag.id IN (:tags)')
                ->setParameter('tags', $tags);
        }

        return $builder;
    }

    private function handleServiceTypeParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (!empty($criteria['serviceType']))
        {
            $builder
                ->andWhere('complaint.serviceType = :serviceType')
                ->setParameter('serviceType', $criteria['serviceType']);
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

    private function handleDatePeriodParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (isset($criteria['startDate']) && isset($criteria['endDate']))
        {
            $startDate = $criteria['startDate'] instanceof \DateTime
                ? $criteria['startDate'] : new \DateTime($criteria['startDate']);

            $endDate = $criteria['endDate'] instanceof \DateTime
                ? $criteria['endDate'] : new \DateTime($criteria['endDate']);

            $builder
                ->andWhere('complaint.createdAt between :startDate and :endDate')
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
                ->andWhere('complaint.region = :region')
                ->setParameter('region', $criteria['region']);
        }

        return $builder;
    }

}
