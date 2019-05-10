<?php

namespace App\Repository;

use App\Entity\Issue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Issue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Issue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Issue[]    findAll()
 * @method Issue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IssueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Issue::class);
    }

    public function getSearchQuery(array $criteria)
    {
        $builder = $this->createQueryBuilder('issue');

        $this->handleClientParameter($builder, $criteria);
        $this->handleCompanyParameter($builder, $criteria);

        $builder->orderBy('issue.createdAt', 'DESC');
        return $builder->getQuery();
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



    // /**
    //  * @return Issue[] Returns an array of Issue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Issue
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
