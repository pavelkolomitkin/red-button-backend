<?php

namespace App\Repository;

use App\Entity\ClientUser;
use App\Entity\CompanyRepresentativeUser;
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
class CompanyRepresentativeUserRepository extends ServiceEntityRepository implements ISearchRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CompanyRepresentativeUser::class);
    }

    function getSearchQuery(array $criteria): Query
    {
        $builder = $this->createQueryBuilder('user');

        $this->handleCompanyParameter($builder, $criteria);

        $builder->orderBy('user.createdAt', 'DESC');

        return $builder->getQuery();
    }

    public function handleCompanyParameter(QueryBuilder $builder, array $criteria)
    {
        if (!empty($criteria['company']))
        {
            $builder
                ->andWhere('user.company = :company')
                ->setParameter('company', $criteria['company']);
        }

        return $builder;
    }
}
