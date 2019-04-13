<?php

namespace App\Repository;

use App\Entity\FederalDistrict;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FederalDistrict|null find($id, $lockMode = null, $lockVersion = null)
 * @method FederalDistrict|null findOneBy(array $criteria, array $orderBy = null)
 * @method FederalDistrict[]    findAll()
 * @method FederalDistrict[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FederalDistrictRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FederalDistrict::class);
    }

    // /**
    //  * @return FederalDistrict[] Returns an array of FederalDistrict objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FederalDistrict
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
