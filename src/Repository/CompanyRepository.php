<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function getSearchCompanyQuery(array $criteria): Query
    {
        $builder = $this->createQueryBuilder('company');

        $this->handleRegionParameter($builder, $criteria);
        $this->handleNameParameter($builder, $criteria);

        $builder->orderBy('company.title', 'ASC');

        return $builder->getQuery();
    }

    private function handleRegionParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (isset($criteria['regionId']))
        {
            $builder->join('company.administrativeUnits', 'unit', 'WITH', 'unit.region = :region')
                ->setParameter('region', $criteria['regionId']);
        }

        return $builder;
    }

    private function handleNameParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (!empty(trim($criteria['name'])))
        {
            $builder->andWhere('lower(company.title) LIKE lower(:name)')
                ->setParameter('name', '%' . trim($criteria['name']) . '%');
        }

        return $builder;
    }

}
