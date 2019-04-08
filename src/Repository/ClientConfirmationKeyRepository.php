<?php

namespace App\Repository;

use App\Entity\ClientConfirmationKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClientConfirmationKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientConfirmationKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientConfirmationKey[]    findAll()
 * @method ClientConfirmationKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientConfirmationKeyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClientConfirmationKey::class);
    }
}
