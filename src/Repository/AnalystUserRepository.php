<?php

namespace App\Repository;

use App\Entity\AnalystUser;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnalystUserRepository extends ServiceEntityRepository
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AnalystUser::class);
    }

    /**
     * @param UserRepository $userRepository
     * @required
     */
    public function setUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * It's used to handle a bug with UniqueEntity constraint when the inheritance is used
     *
     * @param $criteria
     * @return mixed
     */
    public function findByEmail($criteria)
    {
        $result = $this->userRepository->createQueryBuilder('user')
            ->where('user.email = :email')
            ->setParameters($criteria)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
