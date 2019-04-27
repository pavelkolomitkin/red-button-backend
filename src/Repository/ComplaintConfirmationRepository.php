<?php

namespace App\Repository;

use App\Entity\ComplaintConfirmation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    // /**
    //  * @return ComplaintConfirmation[] Returns an array of ComplaintConfirmation objects
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
    public function findOneBySomeField($value): ?ComplaintConfirmation
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
