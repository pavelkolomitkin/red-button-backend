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

        $this->handOwnerParameter($builder, $criteria);

        return $builder->getQuery();
    }

    private function handOwnerParameter(QueryBuilder $builder, array $criteria)
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
