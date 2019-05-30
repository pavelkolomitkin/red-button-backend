<?php

namespace App\Repository;

use App\Entity\PasswordRecoveryKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PasswordRecoveryKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordRecoveryKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordRecoveryKey[]    findAll()
 * @method PasswordRecoveryKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordRecoveryKeyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PasswordRecoveryKey::class);
    }

    // /**
    //  * @return PasswordRecoveryKey[] Returns an array of PasswordRecoveryKey objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PasswordRecoveryKey
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
