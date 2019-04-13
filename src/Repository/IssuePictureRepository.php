<?php

namespace App\Repository;

use App\Entity\IssuePicture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IssuePicture|null find($id, $lockMode = null, $lockVersion = null)
 * @method IssuePicture|null findOneBy(array $criteria, array $orderBy = null)
 * @method IssuePicture[]    findAll()
 * @method IssuePicture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IssuePictureRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IssuePicture::class);
    }

    // /**
    //  * @return IssuePicture[] Returns an array of IssuePicture objects
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
    public function findOneBySomeField($value): ?IssuePicture
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
