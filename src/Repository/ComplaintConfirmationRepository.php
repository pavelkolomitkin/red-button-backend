<?php

namespace App\Repository;

use App\Entity\ComplaintConfirmation;
use App\Entity\ComplaintConfirmationStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComplaintConfirmation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComplaintConfirmation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComplaintConfirmation[]    findAll()
 * @method ComplaintConfirmation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplaintConfirmationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComplaintConfirmation::class);
    }

    public function getSearchQuery(array $criteria)
    {
        $builder = $this->createQueryBuilder('complaint_confirmation');

        $this->handleAddresseeClientParameter($builder, $criteria);
        $this->handleStatusParameter($builder, $criteria);


        $builder->orderBy('complaint_confirmation.createdAt', 'DESC');

        return $builder->getQuery();
    }

    private function handleAddresseeClientParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (isset($criteria['addressee']))
        {
            $builder
                ->join('complaint_confirmation.complaint', 'complaint')
                ->andWhere('complaint.client = :client')
                ->setParameter('client', $criteria['addressee']);
        }

        return $builder;
    }

    private function handleStatusParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (isset($criteria['status']))
        {
            $status = $criteria['status'] instanceof ComplaintConfirmationStatus ? $criteria['status']->getCode() : $criteria['status'];

            $builder
                ->join('complaint_confirmation.status', 'status')
                ->andWhere('status.code = :status')
                ->setParameter('status', $status);
        }

        return $builder;
    }
}
