<?php

namespace App\Repository;

use App\Entity\ComplaintTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComplaintTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComplaintTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComplaintTag[]    findAll()
 * @method ComplaintTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplaintTagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComplaintTag::class);
    }

    public function getSearchQuery(array $criteria)
    {
        $builder = $this->createQueryBuilder('tag');

        $this->handleSearchMask($builder, $criteria);

        $builder->addOrderBy('tag.title', 'ASC');

        return $builder->getQuery();
    }

    private function handleSearchMask(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (!empty($criteria['search']))
        {
            $search = trim($criteria['search']);

            $builder->andWhere('lower(tag.title) LIKE lower(:search)')
                ->setParameter('search', $search . '%');
        }

        return $builder;
    }
}
