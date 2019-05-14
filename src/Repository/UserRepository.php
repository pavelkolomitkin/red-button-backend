<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements ISearchRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getSearchQuery(array $criteria): Query
    {
        $builder = $this->createQueryBuilder('user');

        $this->handleFullNameParameter($builder, $criteria);
        $this->handleEmailParameter($builder, $criteria);

        $builder->orderBy('user.createdAt', 'DESC');

        return $builder->getQuery();
    }

    public function handleFullNameParameter(QueryBuilder $builder, array $criteria)
    {
        if(!empty($criteria['fullName']))
        {
            $builder
                ->andWhere('lower(user.fullName) LIKE lower(:fullName)')
                ->setParameter('fullName', '%' . $criteria['fullName'] . '%');
        }

        return $builder;
    }

    public function handleEmailParameter(QueryBuilder $builder, array $criteria)
    {
        if (!empty($criteria['email']))
        {
            $builder
                ->andWhere('user.email LIKE :email')
                ->setParameter('email', $criteria['email'] . '%');
        }

        return $builder;
    }


}
