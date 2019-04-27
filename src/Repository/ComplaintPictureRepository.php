<?php

namespace App\Repository;

use App\Entity\ComplaintPicture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComplaintPicture|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComplaintPicture|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComplaintPicture[]    findAll()
 * @method ComplaintPicture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplaintPictureRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComplaintPicture::class);
    }

    // /**
    //  * @return ComplaintPicture[] Returns an array of ComplaintPicture objects
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
    public function findOneBySomeField($value): ?ComplaintPicture
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
