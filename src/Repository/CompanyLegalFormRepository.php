<?php

namespace App\Repository;

use App\Entity\CompanyLegalForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CompanyLegalForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyLegalForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyLegalForm[]    findAll()
 * @method CompanyLegalForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyLegalFormRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CompanyLegalForm::class);
    }

    // /**
    //  * @return CompanyLegalForm[] Returns an array of CompanyLegalForm objects
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
    public function findOneBySomeField($value): ?CompanyLegalForm
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
