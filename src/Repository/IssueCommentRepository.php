<?php

namespace App\Repository;

use App\Entity\IssueComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IssueComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method IssueComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method IssueComment[]    findAll()
 * @method IssueComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IssueCommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IssueComment::class);
    }

    public function getSearchQuery(array $criteria) : Query
    {
        $builder = $this->createQueryBuilder('comment');

        $this->handleIssueParameter($builder, $criteria);

        $builder->addOrderBy('comment.createdAt', 'DESC');
        return $builder->getQuery();
    }

    private function handleIssueParameter(QueryBuilder $builder, array $criteria): QueryBuilder
    {
        if (!empty($criteria['issue']))
        {
            $builder
                ->andWhere('comment.issue = :issue')
                ->setParameter('issue', $criteria['issue']);
        }

        return $builder;
    }
}
