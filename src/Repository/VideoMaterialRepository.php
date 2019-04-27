<?php

namespace App\Repository;

use App\Entity\VideoMaterial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VideoMaterial|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoMaterial|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoMaterial[]    findAll()
 * @method VideoMaterial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoMaterialRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VideoMaterial::class);
    }

    // /**
    //  * @return VideoMaterial[] Returns an array of VideoMaterial objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VideoMaterial
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
