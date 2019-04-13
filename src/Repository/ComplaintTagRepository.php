<?php

namespace App\Repository;

use App\Entity\ComplaintTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComplaintTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComplaintTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComplaintTag[]    findAll()
 * @method ComplaintTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplaintTagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComplaintTag::class);
    }

    // /**
    //  * @return ComplaintTag[] Returns an array of ComplaintTag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComplaintTag
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
