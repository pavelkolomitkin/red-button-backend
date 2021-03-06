<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements ISearchRepository, UserLoaderInterface
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

    /**
     * It's used to handle a bug with UniqueEntity constraint when the inheritance is used
     *
     * @param $criteria
     * @return mixed
     */
    public function findByEmail($criteria)
    {
        return $this->createQueryBuilder('user')
                ->where('user.email = :email')
                ->setParameters($criteria)
                ->getQuery()
                ->getResult();
    }


    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('user')
            ->where('user.email = :email')
            ->andWhere('user.isActive = true')
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
